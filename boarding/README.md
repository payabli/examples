
# Merchant Onboarding Form System

A comprehensive merchant onboarding system built with React, Astro.js, TypeScript, and shadcn/ui. This project provides a multi-step form wizard with validation, dynamic fields, and a smooth user experience.

## Features

- 🧙‍♂️ Multi-step form wizard with progress tracking
- 🔄 Dynamic form fields with add/remove functionality
- ✅ Comprehensive form validation using Zod
- 🎨 Styled using Tailwind CSS and shadcn/ui
- 📱 Fully responsive design
- 🌙 Dark mode support
- 🚀 Built on Astro.js for optimal performance
- 😊 Icons support with Lucide

## Project Structure

```
  src/
  ├── components/
  │   ├── ui/               # shadcn/ui components
  │   ├── form/             # Form components
  │   │   ├── FormInput.tsx
  │   │   ├── FormSelect.tsx
  │   │   ├── FormDatePicker.tsx
  │   │   ├── FormCountryRegion.tsx
  │   │   ├── FormSwitch.tsx
  │   │   └── DynamicFormSection.tsx
  │   ├── Wizard.tsx        # Form wizard component
  │   ├── PayabliForm.tsx   # Main form component
  │   ├── ThemeToggle.astro # Toggle dark mode
  │   ├── Header.astro      # Common nav/header
  │   ├── HeadSEO.astro     # Controls SEO meta tags
  │   └── Footer.astro      # Common footer
  ├── pages/
  │   ├── 404.astro         # 404 page
  │   └── index.astro       # Main page
  ├── Schema.ts             # Zod validation schema
  └── onSubmit.ts           # Form submission logic
```

## Setup Instructions

1. Clone this repo.

```bash
    git clone https://github.com/payabli/examples
```

2. Navigate to the project directory.

```bash
    cd examples/boarding
```

3. Install the dependencies.

```bash
    npm install
```

## Form Configuration API

### Wizard Component

The Wizard component manages the multi-step form flow.

```tsx
<Wizard
  currentPage={currentPage}
  setCurrentPage={setCurrentPage}
>
  <WizardStep icon={<User />} label="Step 1">
    {/* Step content */}
  </WizardStep>
  <WizardStep icon={<Atom />} label="Step 2">
    {/* Step content */}
  </WizardStep>
</Wizard>
```
### Form Fields

The form fields accept various different props in order to enhance configurability with minimal code.

1. `name: string` - The name of the field in the Zod schema this input corresponds to.
2. `label: string` - The label for the form field.
3. `tooltip?: string` - An optional tooltip to display when clicking the info icon.
4. `required?: boolean` - Whether the field is required.
5. `disabled?: boolean` - Whether the field is disabled.
6. `showLabel?: boolean` - Conditionally show the label.
7. `showTooltip?: boolean` - Conditionally show the tooltip.

Input fields accept the following props:

1. `iconleft?: ReactNode` - An icon to display on the left side of the input field.
2. `iconright?: ReactNode` - An icon to display on the right side of the input field.
3. `placeholder?: string` - The placeholder text for the input field.
4. `type?: string` - The type of input field (text, email, password).
5. `clearable?: boolean` - Whether the input field should have a clear button.
6. `password?: boolean` - Whether the input field should have a visibility toggle.
7. `mask?: string` - A mask to apply to the input field (phone number, SSN).

Select fields accept the following props:

1. `iconleft?: ReactNode` - An icon to display on the left side of the select field.
2. `options: { value: string; label: string }[]` - An array of options for the select field.

Switch fields accept the following props:
1. `onlabel?: string` - The label for the switch when it is on.
2. `offlabel?: string` - The label for the switch when it is off.

Country Picker fields accept the following props:
1. `whitelist?: string[]` - An array of country codes to whitelist.
2. `blacklist?: string[]` - An array of country codes to blacklist.
3. `priorityOptions?: string[]` - An array of country codes to prioritize.
4. `flag?: boolean` - Whether to display the country flag.
5. `flagsvg?: boolean` - Whether to display the country flag as an SVG.

Region Picker fields accept the following props:
1. `countryCode: string` - The country code to display regions for, usually bound to a country picker.

### Dynamic Sections

The DynamicFormSection component handles repeatable form sections, such as a list of contacts.
This component naturally iterates over a list, so any child Form elements should use the `[]`
syntax to denote which part of the field is being iterated over with an index value.
Ex: `name="contacts[].name"` will iterate over the `contacts` array and set the `name` field for each contact,
while `name="contact.phone[]"` will iterate over the `phone` array within one contact.

```tsx
<DynamicFormSection
  title="Ownership"
  items={owners}
  addItem={addOwner}
  removeItem={removeOwner}
  addButtonText="Add New Owner"
>
  <FormInput
    name="ownership[].ownername"
    label="Owner Name"
    tooltip="Full name of the owner"
  />
</DynamicFormSection>
```

### Validation Schema

Form validation is handled through Zod schemas. Define your schema in `Schema.ts`:

```typescript
import { z } from 'zod'

export const formSchema = z.object({
  // Your schema definition
})

// Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// Create a custom hook to use the form with default values
export function useFormWithSchema(defaultValues: Partial<FormSchemaType> = {}) {
  return useForm<FormSchemaType>({
    resolver: zodResolver(formSchema),
    defaultValues: {
      ...defaultValues,
    },
  })
}
```

### Form Submission

Submission handling is done in `onSubmit.ts`. This file contains a hook that receives the state of the Form Wizard
and returns two functions that handle the form submission in the event of success or failure.

```ts
import { FieldValues } from 'react-hook-form'
import { useFormWithSchema } from './Schema'

type FormSchemaType = z.infer<typeof formSchema>

// 1. Create a hook that returns our success and error handlers
export function useFormLogic(
  steps: React.ReactElement,
  setCurrentPage: React.Dispatch<React.SetStateAction<number>>,
) {
  const form = useFormWithSchema()

  // 2. Create a success handler
  function onSuccess(values: FormSchemaType) {
    console.log(values)
  }

  // 3. Create an error handler
  function onError(errors: FieldValues) {
    form.trigger().then(() => {
      if (steps && steps.props && steps.props.children) {
        console.log('errors', errors)
      } else {
        console.log('Unexpected error!')
      }
    })
  }
}
```

## Styling

The project uses Tailwind CSS with the shadcn/ui default preset. Customize the theme in `globals.css` and point to those values in `tailwind.config.js`:

```css
/* ./src/styles/globals.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  :root {
    /* Light mode CSS variables */
  }

  .dark {
    /* Dark mode CSS variables */
  }
}

@layer base {
  * {
    @apply border-border;
  }

  html {
    @apply scroll-smooth;
  }

  body {
    @apply bg-background text-foreground;
    font-synthesis-weight: none;
    text-rendering: optimizeLegibility;
  }
}
```


```javascript
// ./tailwind.config.js
import type { Config } from 'tailwindcss'
import { fontFamily } from 'tailwindcss/defaultTheme'

const config = {
  darkMode: ['class'],
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  prefix: '',
  theme: {
    // Your CSS variable theme configuration goes here
    // Point your tailwind values to CSS variables defined in globals.css
  },
  plugins: [require("tailwindcss-animate")]
} satisfies Config

export default config
```

## Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/my-feature`
3. Commit your changes: `git commit -am 'Add new feature'`
4. Push to the branch: `git push origin feature/my-feature`
5. Submit a pull request
