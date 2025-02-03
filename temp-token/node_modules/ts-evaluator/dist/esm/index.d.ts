import * as TS from "typescript";
declare const enum LogLevelKind {
    SILENT = 0,
    INFO = 1,
    VERBOSE = 2,
    DEBUG = 3
}
interface EvaluateIOPolicy {
    read: boolean;
    write: boolean;
}
interface EvaluateProcessPolicy {
    exit: boolean;
    spawnChild: boolean;
}
interface EvaluatePolicy {
    io: boolean | EvaluateIOPolicy;
    process: boolean | EvaluateProcessPolicy;
    network: boolean;
    console: boolean;
    deterministic: boolean;
    maxOps: number;
    maxOpDuration: number;
}
interface EvaluatePolicySanitized {
    io: EvaluateIOPolicy;
    process: EvaluateProcessPolicy;
    network: boolean;
    console: boolean;
    deterministic: boolean;
    maxOps: number;
    maxOpDuration: number;
}
type EnvironmentPresetKind = "NONE" | "ECMA" | "BROWSER" | "NODE" | "NODE_CJS" | "NODE_ESM";
// eslint-disable-next-line @typescript-eslint/ban-types
type Literal = object | Function | string | number | boolean | symbol | bigint | null | undefined;
interface IndexLiteral {
    [key: string]: Literal;
}
interface IBindingReportEntry {
    path: string;
    value: unknown;
    node: TS.Node;
}
interface ITraversalReportEntry {
    node: TS.Node;
}
interface IIntermediateResultReportEntry {
    node: TS.Expression | TS.PrivateIdentifier;
    value: unknown;
}
interface IErrorReportEntry {
    node: TS.Node;
    error: Error;
}
type BindingReportCallback = (entry: IBindingReportEntry) => void | Promise<void>;
type ErrorReportCallback = (entry: IErrorReportEntry) => void | Promise<void>;
type IntermediateResultReportCallback = (entry: IIntermediateResultReportEntry) => void | Promise<void>;
type TraversalReportCallback = (entry: ITraversalReportEntry) => void | Promise<void>;
interface IReportingOptions {
    reportBindings: BindingReportCallback;
    reportTraversal: TraversalReportCallback;
    reportIntermediateResults: IntermediateResultReportCallback;
    reportErrors: ErrorReportCallback;
}
type ReportingOptions = Partial<IReportingOptions>;
interface LexicalEnvironment {
    parentEnv: LexicalEnvironment | undefined;
    env: IndexLiteral;
    startingNode: TS.Node;
    preset?: EnvironmentPresetKind;
}
interface IEnvironment {
    preset: EnvironmentPresetKind;
    extra: LexicalEnvironment["env"];
}
interface EvaluateOptions {
    node: TS.Statement | TS.Declaration | TS.Expression;
    typeChecker?: TS.TypeChecker;
    typescript?: typeof TS;
    environment?: Partial<IEnvironment>;
    logLevel?: LogLevelKind;
    policy?: Partial<EvaluatePolicy>;
    reporting?: ReportingOptions;
    /**
     * A record of implementations for module specifiers that will override whatever is resolvable via
     * traditional require(...) evaluation.
     * Useful when/if you want to shim other modules inside the compilation unit contex of the evaluation,
     * much like local identifiers can be overridden with the `environment` option.
     */
    moduleOverrides?: Record<string, unknown>;
}
interface IEvaluationErrorOptions {
    node: TS.Node;
    environment: LexicalEnvironment;
    message?: string;
}
type ThrowError = (error: EvaluationError) => EvaluationError;
/**
 * A Base class for EvaluationErrors
 */
declare class EvaluationError extends Error {
    /**
     * The node that caused or thew the error
     */
    readonly node: TS.Node;
    readonly environment: LexicalEnvironment;
    constructor({ node, environment, message }: IEvaluationErrorOptions);
}
declare function isEvaluationError(item: unknown): item is EvaluationError;
interface IEvaluateResultBase {
    success: boolean;
}
interface IEvaluateSuccessResult extends IEvaluateResultBase {
    success: true;
    value: unknown;
}
interface IEvaluateFailureResult extends IEvaluateResultBase {
    success: false;
    reason: EvaluationError;
}
type EvaluateResult = IEvaluateSuccessResult | IEvaluateFailureResult;
/**
 * Will get a literal value for the given Expression, ExpressionStatement, or Declaration.
 */
declare function evaluate({ typeChecker, node, environment: { preset, extra }, moduleOverrides, typescript, logLevel, policy: { deterministic, network, console, maxOps, maxOpDuration, io, process }, reporting: reportingInput }: EvaluateOptions): EvaluateResult;
interface IMissingCatchOrFinallyAfterTryErrorOptions extends IEvaluationErrorOptions {
    node: TS.TryStatement;
}
/**
 * An Error that can be thrown when a TryStatement is encountered without neither a catch {...} nor a finally {...} block
 */
declare class MissingCatchOrFinallyAfterTryError extends EvaluationError {
    /**
     * The TryStatement that lacks a catch/finally block
     */
    readonly node: TS.TryStatement;
    constructor({ node, environment, message }: IMissingCatchOrFinallyAfterTryErrorOptions);
}
interface IModuleNotFoundErrorOptions extends IEvaluationErrorOptions {
    path: string;
}
/**
 * An Error that can be thrown when a moduleSpecifier couldn't be resolved
 */
declare class ModuleNotFoundError extends EvaluationError {
    /**
     * The path/moduleName that could not be resolved
     */
    readonly path: string;
    constructor({ path, node, environment, message }: IModuleNotFoundErrorOptions);
}
interface INotCallableErrorOptions extends IEvaluationErrorOptions {
    value: Literal;
}
/**
 * An Error that can be thrown when a value is attempted to be called, but isn't callable
 */
declare class NotCallableError extends EvaluationError {
    /**
     * The non-callable value
     */
    readonly value: Literal;
    constructor({ value, node, environment, message }: INotCallableErrorOptions);
}
interface IPolicyErrorOptions extends IEvaluationErrorOptions {
    violation: keyof EvaluatePolicySanitized;
}
/**
 * An Error that can be thrown when a policy is violated
 */
declare class PolicyError extends EvaluationError {
    /**
     * The kind of policy violation encountered
     */
    readonly violation: keyof EvaluatePolicySanitized;
    constructor({ violation, node, environment, message }: IPolicyErrorOptions);
}
interface IUndefinedIdentifierErrorOptions extends IEvaluationErrorOptions {
    node: TS.Identifier | TS.PrivateIdentifier;
}
/**
 * An Error that can be thrown when an undefined identifier is encountered
 */
declare class UndefinedIdentifierError extends EvaluationError {
    /**
     * The identifier that is undefined in the context that created this error
     */
    readonly node: TS.Identifier | TS.PrivateIdentifier;
    constructor({ node, environment, message }: IUndefinedIdentifierErrorOptions);
}
interface IUndefinedLeftValueErrorOptions extends IEvaluationErrorOptions {
}
/**
 * An Error that can be thrown when an undefined leftValue is encountered
 */
declare class UndefinedLeftValueError extends EvaluationError {
    constructor({ node, environment, message }: IUndefinedLeftValueErrorOptions);
}
interface IUnexpectedSyntaxErrorOptions extends IEvaluationErrorOptions {
}
/**
 * An Error that can be thrown when a certain usage is to be considered a SyntaxError
 */
declare class UnexpectedSyntaxError extends EvaluationError {
    constructor({ node, environment, message }: IUnexpectedSyntaxErrorOptions);
}
interface IUnexpectedNodeErrorOptions extends IEvaluationErrorOptions {
    typescript: typeof TS;
}
/**
 * An Error that can be thrown when an unexpected node is encountered
 */
declare class UnexpectedNodeError extends EvaluationError {
    constructor({ node, environment, typescript, message }: IUnexpectedNodeErrorOptions);
}
interface IIoErrorOptions extends IEvaluationErrorOptions {
    kind: keyof EvaluateIOPolicy;
}
/**
 * An Error that can be thrown when an IO operation is attempted to be executed that is in violation of the context policy
 */
declare class IoError extends PolicyError {
    /**
     * The kind of IO operation that was violated
     */
    readonly kind: keyof EvaluateIOPolicy;
    constructor({ node, environment, kind, message }: IIoErrorOptions);
}
interface IMaxOpsExceededErrorOptions extends IEvaluationErrorOptions {
    ops: number;
}
/**
 * An Error that can be thrown when the maximum amount of operations dictated by the policy is exceeded
 */
declare class MaxOpsExceededError extends PolicyError {
    /**
     * The amount of operations performed before creating this error instance
     */
    readonly ops: number;
    constructor({ ops, node, environment, message }: IMaxOpsExceededErrorOptions);
}
interface IMaxOpDurationExceededErrorOptions extends IEvaluationErrorOptions {
    duration: number;
}
/**
 * An Error that can be thrown when the maximum amount of operations dictated by the policy is exceeded
 */
declare class MaxOpDurationExceededError extends PolicyError {
    /**
     * The total duration of an operation that was being performed before exceeding the limit
     */
    readonly duration: number;
    constructor({ duration, environment, node, message }: IMaxOpDurationExceededErrorOptions);
}
interface INetworkErrorOptions extends IEvaluationErrorOptions {
    operation: string;
}
/**
 * An Error that can be thrown when a network operation is attempted to be executed that is in violation of the context policy
 */
declare class NetworkError extends PolicyError {
    /**
     * The kind of operation that was attempted to be performed but was in violation of the policy
     */
    readonly operation: string;
    constructor({ operation, node, environment, message }: INetworkErrorOptions);
}
interface INonDeterministicErrorOptions extends IEvaluationErrorOptions {
    operation: string;
}
/**
 * An Error that can be thrown when something nondeterministic is attempted to be evaluated and has been disallowed to be so
 */
declare class NonDeterministicError extends PolicyError {
    /**
     * The kind of operation that was attempted to be performed but was in violation of the policy
     */
    readonly operation: string;
    constructor({ operation, node, environment, message }: INonDeterministicErrorOptions);
}
interface IProcessErrorOptions extends IEvaluationErrorOptions {
    kind: keyof EvaluateProcessPolicy;
}
/**
 * An Error that can be thrown when a Process operation is attempted to be executed that is in violation of the context policy
 */
declare class ProcessError extends PolicyError {
    /**
     * The kind of process operation that was violated
     */
    readonly kind: keyof EvaluateProcessPolicy;
    constructor({ kind, node, environment, message }: IProcessErrorOptions);
}
export { evaluate, LogLevelKind, EnvironmentPresetKind, IEnvironment, ThrowError, EvaluationError, isEvaluationError, MissingCatchOrFinallyAfterTryError, ModuleNotFoundError, NotCallableError, PolicyError, UndefinedIdentifierError, UndefinedLeftValueError, UnexpectedSyntaxError, UnexpectedNodeError, IoError, MaxOpsExceededError, MaxOpDurationExceededError, NetworkError, NonDeterministicError, ProcessError };
export type { EvaluateResult, EvaluateOptions, BindingReportCallback, IReportingOptions, ReportingOptions };
