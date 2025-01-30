import { useState } from "react"
import { motion, AnimatePresence } from "framer-motion"
import { Loader2, Lock, UserPlus } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { authClient } from "../lib/authClient"

interface FormState {
  name?: string
  email: string
  password: string
  confirmPassword?: string
}

interface FormErrors {
  email?: string
  password?: string
  confirmPassword?: string
  name?: string
}

export default function LoginSignupForm() {
  const [mode, setMode] = useState<"login" | "signup">("login")
  const [isLoading, setIsLoading] = useState(false)
  const [loginForm, setLoginForm] = useState<FormState>({ email: "", password: "" })
  const [signupForm, setSignupForm] = useState<FormState>({ name: "", email: "", password: "", confirmPassword: "" })
  const [errors, setErrors] = useState<FormErrors>({})

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
        window.location.href = "/";
      }, 
      onError: (ctx) => { 
        alert(ctx.error.message); 
      },
    })

    if (error) {
      console.error("Login failed:", error)
      return false
    }

    //console.log(data)

    // Return true if login is successful, false otherwise
    return true
  }

  const handleSignup = async (name: string, email: string, password: string) => {
    // Implement your signup logic here
    const { data, error } = await authClient.signUp.email({ 
      name, 
      email, 
      password,
    }, { 
      onRequest: (ctx) => { 
       //show loading
      }, 
      onSuccess: (ctx) => { 
        window.location.href = "/";
      }, 
      onError: (ctx) => { 
        alert(ctx.error.message); 
      }, 
    })

    console.log("Data: ", data)
    
    if (error) {
      console.error("Signup failed:", error)
      return false
    }

    // Return true if login is successful, false otherwise
    return true
  }

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { id, value } = e.target
    const formKey = id.split("-")[0] as "login" | "signup"
    const field = id.split("-")[1] as keyof FormState
    if (formKey === "login") {
      setLoginForm((prev) => ({ ...prev, [field]: value }))
    } else {
      setSignupForm((prev) => ({ ...prev, [field]: value }))
    }
  }

  const validateEmail = (email: string) => {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
    return re.test(email)
  }

  const validateForm = (form: FormState, type: "login" | "signup"): FormErrors => {
    const errors: FormErrors = {}
    if (type === "signup" && (!form.name || form.name.trim().length === 0)) {
      errors.name = "Name is required"
    }
    if (!form.email || !validateEmail(form.email)) {
      errors.email = "Please enter a valid email address"
    }
    if (form.password.length < 8) {
      errors.password = "Password must be at least 8 characters long"
    }
    if (type === "signup" && form.password !== form.confirmPassword) {
      errors.confirmPassword = "Passwords do not match"
    }
    return errors
  }

  async function onSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()
    const form = mode === "login" ? loginForm : signupForm
    const errors = validateForm(form, mode)

    if (Object.keys(errors).length > 0) {
      setErrors(errors)
      return
    }

    setIsLoading(true)
    setErrors({})

    let success = false
    if (mode === "login") {
      success = await handleLogin(form.email, form.password)
    } else {
      success = await handleSignup(form.name!, form.email, form.password)
    }

    if (success) {
      console.log(`${mode === "login" ? "Login" : "Signup"} successful`)
      // Add any post-login/signup logic here (e.g., redirect to dashboard)
    } else {
      console.log(`${mode === "login" ? "Login" : "Signup"} failed`)
      // Handle failed login/signup (e.g., show error message)
    }

    setIsLoading(false)
  }

  const inputVariants = {
    error: { x: [0, -10, 10, -10, 10, 0], transition: { duration: 0.5 } },
  }

  const cardVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0 },
    exit: { opacity: 0, y: -20 },
  }

  return (
    <div className="flex items-center justify-center min-h-screen p-4">
      <AnimatePresence mode="wait">
        <motion.div
          key={mode}
          initial="hidden"
          animate="visible"
          exit="exit"
          variants={cardVariants}
          transition={{ duration: 0.3 }}
          className="w-full max-w-md"
        >
          <Card className="shadow-lg">
            <CardHeader className="text-center">
              <CardTitle className="text-2xl">Welcome</CardTitle>
              <CardDescription>{mode === "login" ? "Login to your account" : "Create a new account"}</CardDescription>
            </CardHeader>
            <CardContent>
              <form onSubmit={onSubmit}>
                <div className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor={`${mode}-email`}>Email</Label>
                    <motion.div variants={inputVariants} animate={errors.email ? "error" : ""}>
                      <Input
                        id={`${mode}-email`}
                        placeholder="m@example.com"
                        required
                        value={mode === "login" ? loginForm.email : signupForm.email}
                        onChange={handleChange}
                        className={errors.email ? "border-red-500" : ""}
                      />
                    </motion.div>
                    <AnimatePresence>
                      {errors.email && (
                        <motion.p
                          initial={{ opacity: 0, height: 0 }}
                          animate={{ opacity: 1, height: "auto" }}
                          exit={{ opacity: 0, height: 0 }}
                          className="text-red-500 text-sm"
                        >
                          {errors.email}
                        </motion.p>
                      )}
                    </AnimatePresence>
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor={`${mode}-password`}>Password</Label>
                    <motion.div variants={inputVariants} animate={errors.password ? "error" : ""}>
                      <Input
                        id={`${mode}-password`}
                        type="password"
                        required
                        value={mode === "login" ? loginForm.password : signupForm.password}
                        onChange={handleChange}
                        className={errors.password ? "border-red-500" : ""}
                      />
                    </motion.div>
                    <AnimatePresence>
                      {errors.password && (
                        <motion.p
                          initial={{ opacity: 0, height: 0 }}
                          animate={{ opacity: 1, height: "auto" }}
                          exit={{ opacity: 0, height: 0 }}
                          className="text-red-500 text-sm"
                        >
                          {errors.password}
                        </motion.p>
                      )}
                    </AnimatePresence>
                  </div>
                  {mode === "signup" && (
                    <>
                      <div className="space-y-2">
                        <Label htmlFor="signup-confirmPassword">Confirm Password</Label>
                        <motion.div variants={inputVariants} animate={errors.confirmPassword ? "error" : ""}>
                          <Input
                            id="signup-confirmPassword"
                            type="password"
                            required
                            value={signupForm.confirmPassword}
                            onChange={handleChange}
                            className={errors.confirmPassword ? "border-red-500" : ""}
                          />
                        </motion.div>
                        <AnimatePresence>
                          {errors.confirmPassword && (
                            <motion.p
                              initial={{ opacity: 0, height: 0 }}
                              animate={{ opacity: 1, height: "auto" }}
                              exit={{ opacity: 0, height: 0 }}
                              className="text-red-500 text-sm"
                            >
                              {errors.confirmPassword}
                            </motion.p>
                          )}
                        </AnimatePresence>
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="signup-name">Name</Label>
                        <motion.div variants={inputVariants} animate={errors.name ? "error" : ""}>
                          <Input
                            id="signup-name"
                            placeholder="John Doe"
                            required
                            value={signupForm.name}
                            onChange={handleChange}
                            className={errors.name ? "border-red-500" : ""}
                          />
                        </motion.div>
                        <AnimatePresence>
                          {errors.name && (
                            <motion.p
                              initial={{ opacity: 0, height: 0 }}
                              animate={{ opacity: 1, height: "auto" }}
                              exit={{ opacity: 0, height: 0 }}
                              className="text-red-500 text-sm"
                            >
                              {errors.name}
                            </motion.p>
                          )}
                        </AnimatePresence>
                      </div>
                    </>
                  )}
                  <Button className="w-full" type="submit" disabled={isLoading}>
                    {isLoading ? (
                      <>
                        <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                        Please wait
                      </>
                    ) : mode === "login" ? (
                      <>
                        <Lock className="mr-2 h-4 w-4" />
                        Sign In
                      </>
                    ) : (
                      <>
                        <UserPlus className="mr-2 h-4 w-4" />
                        Sign Up
                      </>
                    )}
                  </Button>
                </div>
              </form>
            </CardContent>
            <CardFooter className="flex flex-col items-center justify-center space-y-2">
              <p className="text-sm text-muted-foreground">
                {mode === "login" ? "Don't have an account?" : "Already have an account?"}
              </p>
              <Button variant="link" onClick={() => setMode(mode === "login" ? "signup" : "login")}>
                {mode === "login" ? "Sign up" : "Log in"}
              </Button>
              <p className="text-xs text-muted-foreground text-center mt-4">
                By using this service, you agree to our{" "}
                <a href="#" className="text-primary hover:underline">
                  Terms of Service
                </a>{" "}
                and{" "}
                <a href="#" className="text-primary hover:underline">
                  Privacy Policy
                </a>
              </p>
            </CardFooter>
          </Card>
        </motion.div>
      </AnimatePresence>
    </div>
  )
}

