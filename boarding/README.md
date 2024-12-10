
# Payabli Boarding App

A comprehensive merchant onboarding system built with TypeScript, React, Astro.js, Tailwind, and shadcn/ui. This project provides a responsive multi-step form wizard with validation, data persistence, dynamic fields, a clean and configurable API, and a smooth user experience.

# Why Example Apps?

ISVs need to know how to use the Payabli API. Docs are a huge part of this of course, but there's also a lot to be said
about truly seeing how something works in a real-world scenario. Example apps are a great way to show how to use the API
in a very tangible way. Not only are example apps tangible, but they also provide a starting point for developers to build
off of, saving them time and effort, and enabling to hit the ground running. We as a company want to make it as easy as
possible for developers to integrate with our API, and example apps help us achieve that goal.

One of the most complicated parts of integrating with the Payabli API is the boarding process. This is where the merchant
is onboarded onto the Payabli platform, and where the merchant's information is collected and verified by our beautiful team over
at CRU. This process can be quite complex, and so we've built this example app to show how you can build a boarding app that
is secure, safe, fast, beautiful, and most importantly, CONFIGURABLE. Why is it important to be configurable? Because every partner is different,
every partner has different needs, and every partner has a different brand. This example app is built to be a starting point
for an ISV to build their own boarding app, with their own branding, their own flow, and their own requirements. They can take our code,
which handles a lot of the complexity for them, understand how our API can be used, and then modify what's here to match their existing
user journey and brand consistency. This is the power of example apps, and this is why we've built this one.

Previously at Payabli, we've not really recommended an API-driven boarding solution to customers for a few reasons, one of which is a lack of knowledge
and another is a large amount of complexity. After all, boarding is a very complicated process no matter how you slice it: there's a lot of data to collect,
a lot of verification to do, and a lot of edge cases to handle. But if you have an example app, which shows the "docs in practice", suddenly this process can be
greatly demystified. This is why example apps matter so much for elevating the developer experience at Payabli to the next level, and why we are so stoked
to be starting this new kind of deliverable. In the future, we hope to have many more example apps, each showing a different part of the Payabli API, and putting that
knowledge from the documentation into a living, breathing, real-world form. We are going to save EVERYONE time:
- The docs team isn't going to need to try and shove tons of code examples into doc pages
- The engineers aren't going to need to take as much time away from programming to help generate internal understanding for the API
- The customer success team isn't going to need to spend as much time explaining how the API works to customers
- The ISV is going to be able to hit that crucial "flow state" where they can build their app without interruption


## Summary

As an Payabli partner, you'll need to board merchants onto the Payabli platform in some way to be able to run transactions through them. This is where the process of *boarding* comes in (which you can read about [here](https://docs.payabli.com/developer-guides/boarding-board-merchants)).
This project is an example application that any partner can clone, edit, and deploy to quickly start boarding merchants in a way that is completely controlled and owned by the partner.
The advantage of an approach like this is the fine-grained control over the branding and user jouney as your merchants board.
On the other hand, since you are moving beyond a simple white-labelled, Payabli-hosted page to something truly self-owned and self-hosted, you will naturally have to take on the responsibility of maintaining the application, ensuring it is secure, and keeping it up-to-date with Payabli's APIs and requirements.
Discuss with your Payabli solutions engineer to understand the implications of this approach and to get guidance on how to best proceed.

## Features

- 🧙‍♂️ Multi-step form wizard with progress tracking
- 🔄 Dynamic form fields with add/remove functionality
- ✅ Comprehensive form validation using [Zod](https://github.com/colinhacks/zod)
- 🎨 Styled using [Tailwind CSS](https://tailwindcss.com/)
- 🚧 Built with [shadcn/ui](https://ui.shadcn.com/)
- 📱 Fully responsive design
- 🌙 Dark mode support
- 🚀 Built on [Astro.js](https://astro.build/) for optimal performance
- 😊 Icons support with [Lucide](https://lucide.dev/icons/)
- 💾 Save progress to come back later (encrypted with [IndexedDB](https://developer.mozilla.org/en-US/docs/Web/API/IndexedDB_API))

## Project Structure

```
  src/
  ├── components/
  │   ├── ui/               # shadcn/ui components
  │   ├── form/             # Form components
  │   │   ├── DeleteButton.tsx
  │   │   ├── DynamicFormSection.tsx
  │   │   ├── FormCheckboxGroup.tsx
  │   │   ├── FormInput.tsx
  │   │   ├── FormSelect.tsx
  │   │   ├── FormDatePicker.tsx
  │   │   ├── FormFileUpload.tsx
  │   │   ├── FormCountryRegion.tsx
  │   │   ├── FormSwitch.tsx
  │   │   └── Wizard.tsx
  │   ├── PayabliForm.tsx   # Main form component
  │   ├── ThemeToggle.astro # Toggle dark mode
  │   ├── Header.astro      # Common nav/header
  │   ├── HeadSEO.astro     # Controls SEO meta tags
  │   └── Footer.astro      # Common footer
  ├── layouts/
  │   └── BaseLayout.astro  # Base layout
  ├── pages/
  │   ├── 404.astro         # 404 page
  │   └── index.astro       # Main page
  ├── dbUtils.ts            # Functions to interact with IndexedDB
  ├── Schema.ts             # Zod validation schema
  └── onSubmit.tsx          # Form submission logic
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
  // 1. Create your schema definition
})

// 2. Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// 3. Create a custom hook to use the form with default values
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
