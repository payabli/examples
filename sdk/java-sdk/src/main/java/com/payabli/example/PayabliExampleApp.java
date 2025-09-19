package com.payabli.example;

import io.github.cdimascio.dotenv.Dotenv;
import io.github.payabli.api.PayabliApiClient;
import io.github.payabli.api.PayabliApiClientBuilder;
import io.github.payabli.api.resources.customer.requests.AddCustomerRequest;
import io.github.payabli.api.resources.tokenstorage.requests.AddMethodRequest;
import io.github.payabli.api.resources.tokenstorage.types.*;
import io.github.payabli.api.resources.moneyin.requests.RequestPayment;
import io.github.payabli.api.resources.moneyin.types.*;
import io.github.payabli.api.types.*;
import io.javalin.Javalin;
import io.javalin.http.Context;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.thymeleaf.TemplateEngine;
import org.thymeleaf.templateresolver.ClassLoaderTemplateResolver;

import java.util.*;

public class PayabliExampleApp {
    private static final Logger logger = LoggerFactory.getLogger(PayabliExampleApp.class);
    private static PayabliApiClient payabliClient;
    private static String entryPoint;
    private static String publicToken;
    private static TemplateEngine templateEngine;

    static {
        // Initialize Thymeleaf template engine
        ClassLoaderTemplateResolver templateResolver = new ClassLoaderTemplateResolver();
        templateResolver.setTemplateMode("HTML");
        templateResolver.setPrefix("templates/");
        templateResolver.setSuffix(".html");
        templateResolver.setCharacterEncoding("UTF-8");
        templateResolver.setCacheable(false); // For development
        
        templateEngine = new TemplateEngine();
        templateEngine.setTemplateResolver(templateResolver);
    }

    public static void main(String[] args) {
        // Load environment variables from .env file
        Dotenv dotenv = Dotenv.configure()
                .ignoreIfMissing() // Don't fail if .env doesn't exist
                .load();
        
        // First try to get from .env file, then fall back to system environment
        String apiKey = dotenv.get("PAYABLI_KEY", System.getenv("PAYABLI_KEY"));
        entryPoint = dotenv.get("PAYABLI_ENTRY", System.getenv("PAYABLI_ENTRY"));
        publicToken = dotenv.get("PAYABLI_PUBLIC_TOKEN", System.getenv("PAYABLI_PUBLIC_TOKEN"));

        if (apiKey == null || entryPoint == null || publicToken == null) {
            logger.error("PAYABLI_KEY, PAYABLI_ENTRY, and PAYABLI_PUBLIC_TOKEN environment variables must be set");
            logger.error("Please ensure your .env file exists and contains these variables, or set them as system environment variables");
            System.exit(1);
        }
        
        logger.info("Successfully loaded configuration - Entry Point: {}", entryPoint);

        // Initialize Payabli client
        payabliClient = new PayabliApiClientBuilder()
                .apiKey(apiKey)
                .build();

        // Create and configure Javalin app
        Javalin app = Javalin.create(config -> {
            config.staticFiles.add("static");
        });

        // Routes
        app.get("/", PayabliExampleApp::renderCreateCustomerPage);
        app.get("/list", PayabliExampleApp::renderListCustomersPage);
        app.get("/transaction", PayabliExampleApp::renderTransactionPage);
        app.get("/debug", PayabliExampleApp::renderDebugPage);
        app.post("/api/create", PayabliExampleApp::createCustomer);
        app.get("/api/list", PayabliExampleApp::listCustomers);
        app.delete("/api/delete/{customerId}", PayabliExampleApp::deleteCustomer);
        app.post("/api/transaction/{token}", PayabliExampleApp::processTransaction);

        // Start server
        int port = Integer.parseInt(System.getProperty("server.port", "8000"));
        app.start(port);
        
        logger.info("Payabli SDK Example app started on http://localhost:{}", port);
    }

    private static void renderCreateCustomerPage(Context ctx) {
        org.thymeleaf.context.Context context = new org.thymeleaf.context.Context();
        String html = templateEngine.process("create-customer", context);
        ctx.html(html);
    }

    private static void renderListCustomersPage(Context ctx) {
        org.thymeleaf.context.Context context = new org.thymeleaf.context.Context();
        String html = templateEngine.process("list-customers", context);
        ctx.html(html);
    }

    private static void renderTransactionPage(Context ctx) {
        org.thymeleaf.context.Context context = new org.thymeleaf.context.Context();
        context.setVariable("publicToken", publicToken);
        context.setVariable("entryPoint", entryPoint);
        String html = templateEngine.process("transaction", context);
        ctx.html(html);
    }

    private static void renderDebugPage(Context ctx) {
        org.thymeleaf.context.Context context = new org.thymeleaf.context.Context();
        context.setVariable("publicToken", publicToken);
        context.setVariable("entryPoint", entryPoint);
        context.setVariable("publicTokenDisplay", publicToken != null ? publicToken.substring(0, Math.min(20, publicToken.length())) + "..." : "null");
        String html = templateEngine.process("debug", context);
        ctx.html(html);
    }

    private static void createCustomer(Context ctx) {
        try {
            // Extract form parameters
            String firstname = ctx.formParam("firstname");
            String lastname = ctx.formParam("lastname");
            String email = ctx.formParam("email");
            String timeZoneStr = ctx.formParam("timeZone");
            String address = ctx.formParam("address");
            String city = ctx.formParam("city");
            String state = ctx.formParam("state");
            String zip = ctx.formParam("zip");
            String country = ctx.formParam("country");
            String hvac = ctx.formParam("hvac");
            String electrical = ctx.formParam("electrical");

            // Prepare additional fields
            Map<String, Optional<String>> additionalFields = new HashMap<>();
            if (hvac != null && !hvac.isEmpty()) {
                additionalFields.put("hvac", Optional.of(hvac));
            }
            if (electrical != null && !electrical.isEmpty()) {
                additionalFields.put("electrical", Optional.of(electrical));
            }

            // Create customer data
            CustomerData customerData = CustomerData.builder()
                    .firstname(firstname)
                    .lastname(lastname)
                    .email(email)
                    .timeZone(Integer.parseInt(timeZoneStr))
                    .address(address)
                    .city(city)
                    .state(state)
                    .zip(zip)
                    .country(country)
                    .additionalFields(additionalFields.isEmpty() ? Optional.empty() : Optional.of(additionalFields))
                    .identifierFields(Optional.of(Arrays.asList(Optional.of("email"))))
                    .build();

            // Create the request
            AddCustomerRequest request = AddCustomerRequest.builder()
                    .body(customerData)
                    .forceCustomerCreation(true)
                    .build();

            // Call the Payabli API
            PayabliApiResponseCustomerQuery result = payabliClient.customer().addCustomer(entryPoint, request);
            
            logger.info("Customer created successfully: {}", result);
            
            ctx.html("<input type=\"text\" name=\"valid\" value=\"Success!\" aria-invalid=\"false\" id=\"form-result\" readonly>")
               .status(201);
               
        } catch (Exception e) {
            logger.error("Error creating customer: {}", e.getMessage(), e);
            ctx.html("<input type=\"text\" name=\"invalid\" value=\"Error!\" aria-invalid=\"true\" id=\"form-result\" readonly>")
               .header("HX-Reswap", "innerHTML")
               .status(200);
        }
    }

    private static void listCustomers(Context ctx) {
        try {
            QueryCustomerResponse result = payabliClient.query().listCustomers(entryPoint);
            
            StringBuilder tableRows = new StringBuilder();
            
            if (result.getRecords().isPresent() && !result.getRecords().get().isEmpty()) {
                for (CustomerQueryRecords record : result.getRecords().get()) {
                    String firstname = record.getFirstname().orElse("");
                    String lastname = record.getLastname().orElse("");
                    String email = record.getEmail().orElse("");
                    String address = record.getAddress().orElse("");
                    String city = record.getCity().orElse("");
                    String state = record.getState().orElse("");
                    String zip = record.getZip().orElse("");
                    String timeZone = record.getTimeZone().map(String::valueOf).orElse("");
                    String customerId = record.getCustomerId().map(String::valueOf).orElse("");
                    
                    tableRows.append("<tr>")
                            .append("<td>").append(escapeHtml(firstname)).append("</td>")
                            .append("<td>").append(escapeHtml(lastname)).append("</td>")
                            .append("<td>").append(escapeHtml(email)).append("</td>")
                            .append("<td>").append(escapeHtml(address)).append("</td>")
                            .append("<td>").append(escapeHtml(city)).append("</td>")
                            .append("<td>").append(escapeHtml(state)).append("</td>")
                            .append("<td>").append(escapeHtml(zip)).append("</td>")
                            .append("<td>").append(escapeHtml(timeZone)).append("</td>")
                            .append("<td>")
                            .append("<button id=\"delete\" class=\"outline\"")
                            .append(" hx-delete=\"/api/delete/").append(customerId).append("\"")
                            .append(" hx-swap=\"innerHTML\"")
                            .append(" hx-target=\"closest tr\"")
                            .append(" hx-on=\"htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')\">")
                            .append("X")
                            .append("</button>")
                            .append("</td>")
                            .append("</tr>");
                }
            } else {
                tableRows.append("<tr>")
                        .append("<td colspan=\"9\" style=\"text-align: center; color: #666;\">")
                        .append("No customers found. Create a customer to get started!")
                        .append("</td>")
                        .append("</tr>");
            }
            
            String table = "<table class=\"striped\">" +
                    "<thead>" +
                    "<tr>" +
                    "<th>First Name</th>" +
                    "<th>Last Name</th>" +
                    "<th>Email</th>" +
                    "<th>Address</th>" +
                    "<th>City</th>" +
                    "<th>State</th>" +
                    "<th>Zip</th>" +
                    "<th>Time Zone</th>" +
                    "<th></th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>" +
                    tableRows.toString() +
                    "</tbody>" +
                    "</table>";
            
            ctx.html(table);
            
        } catch (Exception e) {
            logger.error("Error listing customers: {}", e.getMessage(), e);
            ctx.html("<p>Error loading customers. Please check your API credentials and try again.</p>")
               .status(500);
        }
    }

    private static void deleteCustomer(Context ctx) {
        try {
            String customerIdStr = ctx.pathParam("customerId");
            long customerId = Long.parseLong(customerIdStr);
            
            payabliClient.customer().deleteCustomer((int)customerId);
            logger.info("Customer deleted: {}", customerId);
            
            ctx.html("").status(200);
            
        } catch (NumberFormatException e) {
            logger.error("Invalid customer ID format: {}", ctx.pathParam("customerId"));
            ctx.html("<td colspan=\"9\">Invalid customer ID</td>").status(400);
        } catch (Exception e) {
            logger.error("Error deleting customer: {}", e.getMessage(), e);
            ctx.html("<td colspan=\"9\">Error deleting customer: " + e.getMessage() + "</td>").status(500);
        }
    }

    private static String escapeHtml(String input) {
        if (input == null) return "";
        return input
                .replace("&", "&amp;")
                .replace("<", "&lt;")
                .replace(">", "&gt;")
                .replace("\"", "&quot;")
                .replace("'", "&#x27;");
    }

    private static void processTransaction(Context ctx) {
        try {
            String token = ctx.pathParam("token");
            
            if (token == null || token.trim().isEmpty()) {
                ctx.html("<input type=\"text\" name=\"invalid\" value=\"No token provided\" aria-invalid=\"true\" readonly>")
                   .status(400);
                return;
            }

            System.out.println("\n");
            System.out.println("========================================================================");
            System.out.println("🚀 STARTING PAYMENT PROCESSING WITH TOKEN: " + token);
            System.out.println("========================================================================");
            logger.info("Converting temporary token to permanent: {}", token);

            // Step 1: Use token storage to convert temporary token to permanent
            System.out.println("\n📦 STEP 1: Converting temporary token to permanent stored method...");
            AddMethodRequest tokenRequest = AddMethodRequest.builder()
                .body(RequestTokenStorage.builder()
                    .customerData(PayorDataRequest.builder()
                        .customerId(4440L) // This should be dynamic based on your needs
                        .build())
                    .entryPoint(entryPoint)
                    .paymentMethod(RequestTokenStoragePaymentMethod.of(
                        ConvertToken.builder()
                            .method("card")
                            .tokenId(token) // The temporary token from the embedded component
                            .build()))
                    .source("web")
                    .methodDescription("Main card")
                    .build())
                .createAnonymous(true)
                .temporary(false)
                .build();

            System.out.println("\n\n\n\n\n⏳ Calling TokenStorage.addMethod()...\n\n\n\n\n\n\n");
            var tokenResult = payabliClient.tokenStorage().addMethod(tokenRequest);
            System.out.println("\n\n\n\n\n\n\n✅ TokenStorage.addMethod() completed!\n\n\n\n\n\n\n");
            System.out.println("📋 Token storage result: " + tokenResult);
            logger.info("Token storage result: {}", tokenResult);
            
            String storedMethodId = tokenResult.getResponseData().get().getReferenceId().get();
            if (storedMethodId == null || storedMethodId.isEmpty()) {
                throw new RuntimeException("Failed to get stored method ID from token storage response");
            }
            
            System.out.println("🎯 STORED METHOD ID: " + storedMethodId);
            logger.info("Token stored successfully with ID: {}", storedMethodId);

            // Step 2: Process payment using the stored method
            System.out.println("\n💳 STEP 2: Processing payment using stored method...");
            RequestPayment paymentRequest = RequestPayment.builder()
                .body(TransRequestBody.builder()
                    .paymentDetails(PaymentDetail.builder()
                        .totalAmount(100.0) // This should be dynamic based on your needs
                        .serviceFee(0.0)
                        .build())
                    .paymentMethod(PaymentMethod.of(
                        PayMethodStoredMethod.builder()
                            .method(PayMethodStoredMethodMethod.CARD)
                            .storedMethodId(storedMethodId)
                            .initiator("payor")
                            .storedMethodUsageType("unscheduled")
                            .build()))
                    .customerData(PayorDataRequest.builder()
                        .customerId(4440L)
                        .build())
                    .entryPoint(entryPoint)
                    .ipaddress("255.255.255.255") // This should be dynamic based on request
                    .build())
                .build();

            System.out.println("⏳ Calling MoneyIn.getpaid()...");
            var paymentResult = payabliClient.moneyIn().getpaid(paymentRequest);
            System.out.println("✅ MoneyIn.getpaid() completed!");
            System.out.println("💰 Payment result: " + paymentResult);
            logger.info("Payment processed successfully: {}", paymentResult);

            System.out.println("\n========================================================================");
            System.out.println("🎉 PAYMENT PROCESSING COMPLETED SUCCESSFULLY!");
            System.out.println("========================================================================");
            System.out.println("\n");

            ctx.html("<input type=\"text\" name=\"valid\" value=\"Payment processed successfully!\" aria-invalid=\"false\" readonly>")
               .status(200);

        } catch (Exception e) {
            System.out.println("\n");
            System.out.println("========================================================================");
            System.out.println("❌ PAYMENT PROCESSING FAILED!");
            System.out.println("🔥 ERROR: " + e.getMessage());
            System.out.println("========================================================================");
            System.out.println("\n");
            logger.error("Error processing transaction: {}", e.getMessage(), e);
            ctx.html("<input type=\"text\" name=\"invalid\" value=\"Error processing transaction: " + escapeHtml(e.getMessage()) + "\" aria-invalid=\"true\" readonly>")
               .status(200);
        }
    }
}