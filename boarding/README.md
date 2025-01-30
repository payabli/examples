
# Payabli Boarding App

A comprehensive merchant onboarding system built with TypeScript, React, Astro.js, Tailwind, and shadcn/ui. This project provides a responsive multi-step form wizard with validation, data persistence, dynamic fields, a clean and configurable API, and a smooth user experience.

## Summary

As an Payabli partner, you'll need to board merchants onto the Payabli platform in some way to be able to run transactions through them. This is where the process of *boarding* comes in (which you can read about [here](https://docs.payabli.com/developer-guides/boarding-board-merchants)).
This project is an example application that any partner can clone, edit, and deploy to quickly start boarding merchants in a way that is completely controlled and owned by the partner.
The advantage of an approach like this is the fine-grained control over the branding and user jouney as your merchants board.
On the other hand, since you are moving beyond a simple white-labelled, Payabli-hosted page to something truly self-owned and self-hosted, you will naturally have to take on the responsibility of maintaining the application, ensuring it is secure, and keeping it up-to-date with Payabli's APIs and requirements.
Discuss with your Payabli solutions engineer to understand the implications of this approach and to get guidance on how to best proceed.

## Features

- 🧙‍♂️ Multi-step form wizard with progress tracking
- 🔄 Dynamic form fields with add/remove functionality
- 📱 Fully responsive design
- 🌙 Dark mode support
- ✅ Comprehensive form validation using [Zod](https://github.com/colinhacks/zod)
- 🎨 Styled using [Tailwind CSS](https://tailwindcss.com/)
- 🚧 Built with [shadcn/ui](https://ui.shadcn.com/)
- 🚀 Built on [Astro.js](https://astro.build/) for optimal performance
- 😊 Icons support with [Lucide](https://lucide.dev/icons/)
- 💾 Save progress to come back later (encrypted with [FingerprintJS](https://github.com/fingerprintjs/fingerprintjs) and stored via [DrizzleORM](https://orm.drizzle.team/))
- 🔒 All routes secured by [better-auth](https://www.better-auth.com/)

## Project Structure

```
  .env                          # Put your API token and environment here
  .env.template                 # Template to copy and edit
  auth.ts                       # better-auth configuration
  src/
  ├── components/
  │   ├── ui/                   # shadcn/ui components
  │   ├── form/                 # Form components
  │   │   ├── DeleteButton.tsx
  │   │   ├── DynamicFormSection.tsx
  │   │   ├── ESignature.tsx
  │   │   ├── FormCheckboxGroup.tsx
  │   │   ├── FormCountryRegion.tsx
  │   │   ├── FormFileUpload.tsx
  │   │   ├── FormInput.tsx
  │   │   ├── FormSelect.tsx
  │   │   ├── FormSwitch.tsx
  │   │   ├── FormWrapper.tsx   # Base for all form fields
  │   │   └── Wizard.tsx
  │   ├── PayabliForm.tsx       # Main form component
  │   ├── LoginForm.tsx         # Login form component
  │   ├── ThemeToggle.astro     # Toggle dark mode
  │   ├── SessionControls.astro # Toggle dark mode
  │   ├── Header.astro          # Common nav/header
  │   ├── HeadSEO.astro         # Controls SEO meta tags
  │   └── Footer.astro          # Common footer
  ├── layouts/
  │   └── BaseLayout.astro      # Base layout
  ├── pages/
  │   ├── api/                  # API routes
  │   │   ├── formData.tsx      # Save form data
  │   │   ├── createApp.tsx     # Create Boarding application
  │   │   ├── attachFiles.tsx   # Attach e-signature/files to application
  │   │   └── submitApp.tsx     # Change app status to submitted
  │   ├── 404.astro             # 404 page
  │   ├── login.astro           # login/sign-up page
  │   └── index.astro           # Main page
  ├── lib/                      # Utility functions
  │   ├── authClient.ts         # better-auth client-side logic 
  │   ├── clientDb.ts           # Client-side DB logic
  │   ├── getUrl.ts             # Get URL from .env
  │   ├── helpers.ts            # Country/region data functions
  │   ├── serverDb.ts           # Server-side DB logic
  │   └── utils.ts              # Miscellaneous utility functions
  ├── middleware.ts             # Middleware (authentication)
  ├── db.ts                     # Basic DB schema
  ├── Schema.ts                 # Zod validation schema
  └── onSubmit.tsx              # Form submission logic
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

4. Copy the `.env.template` file to `.env` and fill in the required values.

```bash
cp .env.template .env
```

5. Set up better-auth.
```bash
npx @better-auth/cli generate
npx @better-auth/cli migrate
```

6. Start the development server.

```bash
npm run dev
```

## Form Configuration API

### Wizard Component

The Wizard component manages the multi-step form flow.

```tsx
<Wizard
  currentPage={currentPage}
  setCurrentPage={setCurrentPage}
  preChildren={componentThatAppearsAboveSteps}
  postChildren={componentThatAppearsBelowSteps}
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
8. `numeric?: boolean` - Whether the input field should only accept numbers.
9. `includeMaskedChars?: boolean` - Whether to include the mask characters in the value.
10. `maxLength?: number` - The maximum length of the input field.

Select fields accept the following props:

1. `iconleft?: ReactNode` - An icon to display on the left side of the select field.
2. `options: { value: string; label: string }[]` - An array of options for the select field.
3. `option: { type: 'label'; label: string }` - An option to create a non-value separator, i.e. "North America".

Switch fields accept the following props:
1. `onlabel?: string` - The label for the switch when it is on.
2. `offlabel?: string` - The label for the switch when it is off.

Country picker fields accept the following props:
1. `whitelist?: string[]` - An array of country codes to whitelist.
2. `blacklist?: string[]` - An array of country codes to blacklist.
3. `priorityOptions?: string[]` - An array of country codes to prioritize.
4. `flag?: boolean` - Whether to display the country flag.
5. `flagsvg?: boolean` - Whether to display the country flag as an SVG.

Region picker fields accept the following props:
1. `countryCode: string` - The country code to display regions for, usually bound to a country picker.

A combined country and region picker field is available and recommended for use in tandem as opposed to manual
state management. It accepts the same props as the country picker and region picker fields, prefixed by either
`country` or `region` in camel case, i.e. `countryName` or `regionTooltip`.

File uploads accept the following props:
1. `maxSizeMB?: number` - The maximum file size allowed in megabytes.
2. `accept?: string` - The file types that are accepted. This is a string that specifies the file extensions or MIME types.
3. `disabled?: boolean`- Whether the file upload is disabled.
4. `file: File | null` - The selected file. This is a File object or null if no file is selected.
5. `setFile: (file: File | null) => void` - A function to update the selected file.
6. `type: string` - The file type. This is a string that represents the type of file (e.g., "image/jpeg").
7. `setType: (type: string) => void` - A function to update the file type.
8. `extension: string` - The file extension. This is a string that represents the file extension (e.g., "jpg").
9. `setExtension: (extension: string) => void` - A function to update the file extension.
10. `contents: string | null` - The contents of the file. This is a string or null if the file has no contents (e.g., for binary files).
11. `setContents: (contents: string | null) => void` - A function to update the file contents.
12. `id?: string` - An ID for the file upload component (necessary if multiple are present).

Checkbox groups accept the following props:
1. `options: { name: string, label: string }[]` - An array of options for the checkbox group.

> [!NOTE]
> All form components use the `FormWrapper.tsx` component as a base for shared props and formatting, with the exception of the checkbox group component.


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
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'

// 1. Create your schema definition
export const formSchema = z.object({
  pizza: z.object({
    toppings: z.array(z.string()).nonempty(),
    crust: z.string().required(),
    redSauce: z.boolean().default(true)
    extraSeasoning: z.boolean().default(false)
    specialInstructions: z.string().optional()
  })
}).superRefine((data, ctx) => {
  // complicated validation logic
  if (data.pizza.toppings.includes('anchovies')) {
    ctx.addIssue({
        path: ['pizza.toppings'],
        fatal: false,
        code: 'custom',
        message: 'We do not carry anchovies.',
    })
  }
})

// 2. Create a type for the form data
export type FormSchemaType = z.infer<typeof formSchema>

// 3. Create a custom hook to use the form
export function useFormWithSchema(defaultValues: Partial<FormSchemaType> = {}) {
  return useForm<FormSchemaType>({
    resolver: zodResolver(formSchema),
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

## Database Setup

The initial database setup is done in `db.ts`. This file setups the connection and exports the scaffolded database.
By default, it is using sqlite3 with Drizzle ORM. You can easily swap out the underlying database technology.
This database is not used for sending the finished form data by default, but only for saving form progress data
before validation.

```ts
import { drizzle } from 'drizzle-orm/better-sqlite3'
import { sql } from 'drizzle-orm'
import { sqliteTable, text } from 'drizzle-orm/sqlite-core'
import Database from 'better-sqlite3'

// Define the schema
export const formData = sqliteTable('formData', {
  deviceToken: text('deviceToken').primaryKey(),
  data: text('data').notNull(),
})

// Create a database connection
const sqlite = new Database('form.db')
export const db = drizzle(sqlite)

// Create the table if it doesn't exist
db.run(sql`
  CREATE TABLE IF NOT EXISTS formData (
    deviceToken TEXT PRIMARY KEY,
    data TEXT NOT NULL
  )
`)
```

Once your initial setup is complete, you can define functions to access your database in `lib/clientDb.ts` and
`lib/serverDb.ts`. Here's an example function for `serverDb.ts`:

```ts
export async function loadFormData(deviceToken: string) {
  const result = await db.select().from(formData).where(sql`${formData.deviceToken} = ${deviceToken}`);
  return result[0]?.data || null;
}
```

### API Routes

This project uses a Payabli API token, which can't be shared publicly to preserve security. In order to accomodate this,
when the client needs to make an API call to Payabli's API, it actually calls to the server's API routes, which then go to the
Payabli API. This way, no sensitive information is exposed in the client. The API routes are in `pages/api`.

The `formData` route is used for saving the client-side form data's progress via the `Save Progress` button, and stores it
in a local sqlite database.

The other three routes follow this flow:

1. `createApp` - When you click `Confirm` on the final page of the form, the server will create an application within Payabli via a `POST` call to Payabli's API.
2. `attachFiles` - After the e-signature is completed, the server will attach the signed document,
as well as any other files (such as the images of the voided checks for proof of account) to the application via a `PUT` call to Payabli's API.
3. `submitApp` - Finally, the server will submit the application via a `GET` call to Payabli's API, which changes the internal status
of the application to `Submitted`.

Here's the entire `api/createApp.ts` file as an example:

```ts
import type { APIRoute } from 'astro'
import { getApiUrlPrefix } from '../../lib/getUrl';

export const POST: APIRoute = async ({ request }) => {
  const apiToken = import.meta.env.PAYABLI_API_TOKEN
  const prefix = getApiUrlPrefix()

  try {
    const formData = await request.json()
    const jsonData = JSON.stringify(formData)
    const response = await fetch(`https://api${prefix}.payabli.com/api/Boarding/app`, {
      method: 'POST',
      headers: { 'content-type': 'application/json', 'requestToken': apiToken },
      body: jsonData
    })

    if (!response.ok) { throw new Error(`HTTP error! status: ${response.status}`) }

    const responseBody = await response.json()

    return new Response(JSON.stringify(responseBody.responseData), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    })
  } catch (error) {
    console.error('Error submitting application:', error)
    return new Response(JSON.stringify({ error: 'Failed to submit application' }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    })
  }
}
```

## Authentication 

This project uses [better-auth](https://www.better-auth.com/) for authentication. The `auth.ts` file contains the configuration for the authentication client.
If you want to add email verification, password reset, social logins (Google, Facebook, GitHub), or any other features, this is the place to do it.
Visit the [better-auth documentation](https://www.better-auth.com/docs) for more information on how to configure it.
```ts 
// auth.ts
import { betterAuth } from "better-auth";
import Database from "better-sqlite3";
 
export const auth = betterAuth({
    database: new Database("./auth.db"),
    emailAndPassword: {
      enabled: true,
    }
})
```

The `src/middleware.ts` file contains the middleware that checks if the user is authenticated before allowing access to the boarding app.
Here, you can define which routes require authentication and which do not, or add custom logic.
```ts
import { auth } from "../auth"; // import your Better Auth instance
import { defineMiddleware } from "astro:middleware";

export const onRequest = defineMiddleware(async (context, next) => {
  const isAuthed = await auth.api
    .getSession({
      headers: context.request.headers,
    })
  if ((context.url.pathname === "/" || 
       context.url.pathname === "/api/attachFiles" || 
       context.url.pathname === "/api/createApp" || 
       context.url.pathname === "/api/formData" || 
       context.url.pathname === "/api/submitApp") && !isAuthed) {
    return context.redirect("/login");
  }
  return next();
});
```

To edit the login form itself, go to `src/components/LoginForm.tsx`. 
Here, you can call methods on `authClient` to perform actions like logging in, signing up, or logging out.
If you set up email verification, password reset, or social logins in `auth.ts`, you can call those methods here as well.
```ts
const handleLogin = async (email: string, password: string) => {
// Implement your login logic here
const { data, error } = await authClient.signIn.email({ 
  email, 
  password,
}, { 
  onRequest: (ctx) => { 
    //show loading
  }, 
  onSuccess: (ctx) => { 
    // redirect to home page
    window.location.href = "/";
  }, 
  onError: (ctx) => { 
    // show error message
    alert(ctx.error.message); 
  },
})

if (error) {
  console.error("Login failed:", error)
  return false
}

// Return true if login is successful, false otherwise
return true
}
```

> [!NOTE]
> When you deploy your app to production, make sure to change the `BETTER_AUTH_URL` environment variable in your `.env` file.

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
