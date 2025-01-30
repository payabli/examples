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
