import { z } from 'zod'

/**
 * A prefill value must be both a valid raw input and the final parsed value.
 * This keeps enforced defaults concrete even for coercing schemas.
 */
type PrefillValue<TSchema extends z.ZodTypeAny> = z.output<TSchema> & z.input<TSchema>

/**
 * Wraps a schema field with an enforced value that is used for client defaults
 * and re-applied on the server before parsing.
 */
export type PrefillField<
  TSchema extends z.ZodTypeAny,
  TValue extends PrefillValue<TSchema> = PrefillValue<TSchema>,
> = {
  kind: 'prefill'
  schema: TSchema
  value: TValue
}

/** A schema shape entry can be either a plain Zod field or a prefilled field. */
type FormShapeField = z.ZodTypeAny | PrefillField<z.ZodTypeAny, any>

/** The raw object shape accepted by the schema kit builder. */
type FormShapeDefinition = Record<string, FormShapeField>

/** Maps each field to the client-facing schema variant used by the form. */
type ClientField<TField extends FormShapeField> =
  TField extends PrefillField<infer TSchema>
    ? z.ZodDefault<TSchema>
    : Extract<TField, z.ZodTypeAny>

/** Maps each field to the authoritative server-side schema variant. */
type ServerField<TField extends FormShapeField> =
  TField extends PrefillField<infer TSchema>
    ? TSchema
    : Extract<TField, z.ZodTypeAny>

/** Builds the full client schema shape from the provided field definitions. */
type ClientShape<TShape extends FormShapeDefinition> = {
  [TKey in keyof TShape]: ClientField<TShape[TKey]>
}

/** Builds the full server schema shape from the provided field definitions. */
type ServerShape<TShape extends FormShapeDefinition> = {
  [TKey in keyof TShape]: ServerField<TShape[TKey]>
}

/** Extracts only the enforced values from prefilled fields. */
type EnforcedValues<TShape extends FormShapeDefinition> = {
  [TKey in keyof TShape as TShape[TKey] extends PrefillField<z.ZodTypeAny, any>
    ? TKey
    : never]: TShape[TKey] extends PrefillField<z.ZodTypeAny, infer TValue>
    ? TValue
    : never
}

/**
 * Default values are partial form values plus any enforced fields that must
 * always be present for the client.
 */
type DefaultValues<TShape extends FormShapeDefinition> = Partial<
  z.output<z.ZodObject<ClientShape<TShape>>>
> &
  EnforcedValues<TShape>

/**
 * Normalization preserves the input shape while replacing enforced keys with
 * their trusted server values.
 */
type NormalizeInputResult<
  TShape extends FormShapeDefinition,
  TInput,
> = TInput extends Record<string, unknown>
  ? Omit<TInput, keyof EnforcedValues<TShape>> & EnforcedValues<TShape>
  : TInput

/** The typed API returned by the schema kit builder. */
type SchemaKit<TShape extends FormShapeDefinition> = {
  clientSchema: z.ZodObject<ClientShape<TShape>>
  serverSchema: z.ZodObject<ServerShape<TShape>>
  defaultValues: DefaultValues<TShape>
  normalizeInput<TInput>(input: TInput): NormalizeInputResult<TShape, TInput>
  parseNormalized(input: unknown): z.output<z.ZodObject<ServerShape<TShape>>>
  safeParseNormalized(input: unknown): ReturnType<z.ZodObject<ServerShape<TShape>>['safeParse']>
}

/**
 * Declares a field as client-prefilled and server-enforced.
 *
 * A prefilled field behaves like a normal schema field in the form, but the
 * provided value is also treated as trusted server state. The schema kit uses
 * it in two places:
 * - as a client default value so forms start with the expected value
 * - as an enforced server value so incoming payloads cannot override it
 *
 * Use this for fields that should look like ordinary form data on the client
 * while still being owned by the server once the payload is submitted.
 */
export function prefill<TSchema extends z.ZodTypeAny>(
  schema: TSchema,
  value: PrefillValue<TSchema>,
): PrefillField<TSchema, PrefillValue<TSchema>> {
  return {
    kind: 'prefill',
    schema,
    value,
  }
}

/** Identifies schema entries that carry an enforced prefill value. */
function isPrefillField(
  field: FormShapeField,
): field is PrefillField<z.ZodTypeAny, any> {
  return (
    typeof field === 'object' &&
    field !== null &&
    'kind' in field &&
    field.kind === 'prefill'
  )
}

/**
 * Builds paired client/server schemas plus helpers from a single field
 * definition object.
 *
 * The returned kit keeps one schema declaration as the source of truth while
 * deriving two different runtime views:
 * - a client schema that includes defaults for prefilled fields
 * - a server schema that treats those same fields as authoritative values
 *
 * It also exposes helpers for:
 * - `defaultValues`, which can be passed directly into form initialization
 * - `normalizeInput`, which reapplies enforced values without full validation
 * - `parseNormalized`, which normalizes first and then fully parses
 * - `safeParseNormalized`, which does the same without throwing
 *
 * This is the main bridge between declarative schema authoring and the server
 * enforcement pipeline.
 */
export function createFormSchemaKit<TShape extends FormShapeDefinition>(
  shape: TShape,
): SchemaKit<TShape> {
  const clientShape = {} as ClientShape<TShape>
  const serverShape = {} as ServerShape<TShape>
  const defaultValues = {} as DefaultValues<TShape>
  const enforcedValues = {} as EnforcedValues<TShape>

  /** Split the single declared shape into client and server variants. */
  for (const key of Object.keys(shape) as Array<keyof TShape>) {
    const field = shape[key]

    if (isPrefillField(field)) {
      clientShape[key] = field.schema.default(field.value) as ClientShape<TShape>[typeof key]
      serverShape[key] = field.schema as ServerShape<TShape>[typeof key]
      ;(defaultValues as Record<string, unknown>)[key as string] = field.value
      ;(enforcedValues as Record<string, unknown>)[key as string] = field.value
      continue
    }

    clientShape[key] = field as ClientShape<TShape>[typeof key]
    serverShape[key] = field as ServerShape<TShape>[typeof key]
  }

  /** Replaces enforced keys on arbitrary input without requiring full validation. */
  function normalizeInput<TInput>(input: TInput): NormalizeInputResult<TShape, TInput> {
    if (!input || typeof input !== 'object' || Array.isArray(input)) {
      return input as NormalizeInputResult<TShape, TInput>
    }

    return {
      ...(input as Record<string, unknown>),
      ...enforcedValues,
    } as NormalizeInputResult<TShape, TInput>
  }

  const clientSchema = z.object(clientShape) as z.ZodObject<ClientShape<TShape>>
  const serverSchema = z.object(serverShape) as z.ZodObject<ServerShape<TShape>>

  /** Normalizes first, then parses with the authoritative server schema. */
  function parseNormalized(input: unknown) {
    return serverSchema.parse(normalizeInput(input))
  }

  /** Normalizes first, then safe-parses with the authoritative server schema. */
  function safeParseNormalized(input: unknown) {
    return serverSchema.safeParse(normalizeInput(input))
  }

  return {
    clientSchema,
    serverSchema,
    defaultValues,
    normalizeInput,
    parseNormalized,
    safeParseNormalized,
  }
}
