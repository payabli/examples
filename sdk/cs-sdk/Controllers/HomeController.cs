using Microsoft.AspNetCore.Mvc;
using PayabliApi;
using PayabliApi.Core;
using PayabliSdkExample.Services;

namespace PayabliSdkExample.Controllers
{
    public class HomeController : Controller
    {
        private readonly PayabliApiClient _payabliClient;
        private readonly ConfigurationService _configService;

        public HomeController(PayabliApiClient payabliClient, ConfigurationService configService)
        {
            _payabliClient = payabliClient;
            _configService = configService;
        }

        public IActionResult Index()
        {
            return View();
        }

        public IActionResult List()
        {
            return View();
        }

        [HttpPost]
        [Route("/api/create")]
        public async Task<IActionResult> CreateCustomer(CustomerFormModel model)
        {
            try
            {
                var additionalFields = new Dictionary<string, string?>();
                if (!string.IsNullOrEmpty(model.Hvac))
                    additionalFields["hvac"] = model.Hvac;
                if (!string.IsNullOrEmpty(model.Electrical))
                    additionalFields["electrical"] = model.Electrical;

                var customerData = new CustomerData
                {
                    Firstname = model.Firstname,
                    Lastname = model.Lastname,
                    Email = model.Email,
                    Zip = model.Zip,
                    TimeZone = int.Parse(model.TimeZone),
                    Country = model.Country,
                    State = model.State,
                    City = model.City,
                    Address = model.Address,
                    AdditionalFields = additionalFields.Count > 0 ? additionalFields : null,
                    IdentifierFields = new[] { "email" }
                };

                var request = new AddCustomerRequest
                {
                    ForceCustomerCreation = true,
                    Body = customerData
                };

                var result = await _payabliClient.Customer.AddCustomerAsync(_configService.EntryPoint, request);
                Console.WriteLine($"Customer created successfully: {result}");

                return Content(
                    "<input type=\"text\" name=\"valid\" value=\"Success!\" aria-invalid=\"false\" id=\"form-result\" readonly>",
                    "text/html"
                );
            }
            catch (PayabliApiApiException ex)
            {
                Console.WriteLine($"API Error: {ex.Message}");
                return Content(
                    "<input type=\"text\" name=\"invalid\" value=\"Error!\" aria-invalid=\"true\" id=\"form-result\" readonly>",
                    "text/html"
                );
            }
            catch (PayabliApiException ex)
            {
                Console.WriteLine($"API Error: {ex.Message}");
                return Content(
                    "<input type=\"text\" name=\"invalid\" value=\"Error!\" aria-invalid=\"true\" id=\"form-result\" readonly>",
                    "text/html"
                );
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Unexpected error: {ex.Message}");
                return Content(
                    "<input type=\"text\" name=\"invalid\" value=\"Error!\" aria-invalid=\"true\" id=\"form-result\" readonly>",
                    "text/html"
                );
            }
        }

        [HttpGet]
        [Route("/api/list")]
        public async Task<IActionResult> GetCustomers()
        {
            try
            {
                var request = new ListCustomersRequest
                {
                    LimitRecord = 100 // Get up to 100 customers
                };

                var result = await _payabliClient.Query.ListCustomersAsync(_configService.EntryPoint, request);

                var tableRows = "";
                if (result.Records?.Any() == true)
                {
                    foreach (var record in result.Records)
                    {
                        tableRows += $@"
                        <tr>
                          <td>{record.Firstname ?? ""}</td>
                          <td>{record.Lastname ?? ""}</td>
                          <td>{record.Email ?? ""}</td>
                          <td>{record.Address ?? ""}</td>
                          <td>{record.City ?? ""}</td>
                          <td>{record.State ?? ""}</td>
                          <td>{record.Zip ?? ""}</td>
                          <td>{record.TimeZone?.ToString() ?? ""}</td>
                          <td>
                            <button id=""delete"" class=""outline""
                              hx-delete=""/api/delete/{record.CustomerId}"" 
                              hx-swap=""innerHTML"" 
                              hx-target=""closest tr""  
                              hx-on=""htmx:beforeRequest: this.setAttribute('aria-busy', 'true'), htmx:afterRequest: this.removeAttribute('aria-busy')""
                            >
                              ❌
                            </button>
                          </td>
                        </tr>";
                    }
                }
                else
                {
                    tableRows = @"
                        <tr>
                          <td colspan=""9"" style=""text-align: center; color: #666;"">
                            No customers found. Create a customer to get started!
                          </td>
                        </tr>";
                }

                var table = $@"
                <table class=""striped"">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Address</th>
                      <th>City</th>
                      <th>State</th>
                      <th>Zip</th>
                      <th>Time Zone</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    {tableRows}
                  </tbody>
                </table>";

                return Content(table, "text/html");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error listing customers: {ex.Message}");
                return Content(
                    "<p>Error loading customers. Please check your API credentials and try again.</p>",
                    "text/html"
                );
            }
        }

        [HttpDelete]
        [Route("/api/delete/{id}")]
        public async Task<IActionResult> DeleteCustomer(int id)
        {
            try
            {
                await _payabliClient.Customer.DeleteCustomerAsync(id);
                Console.WriteLine($"Customer {id} deleted successfully");
                return Content("", "text/html");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error deleting customer {id}: {ex.Message}");
                return Content(
                    $"<td colspan=\"9\">Error deleting customer: {ex.Message}</td>",
                    "text/html"
                );
            }
        }

        public IActionResult Transaction()
        {
            ViewBag.EntryPoint = _configService.EntryPoint;
            ViewBag.PublicToken = _configService.PublicToken;
            Console.WriteLine($"Transaction page - EntryPoint: {_configService.EntryPoint}");
            Console.WriteLine($"Transaction page - PublicToken: {_configService.PublicToken?.Substring(0, Math.Min(50, _configService.PublicToken.Length))}...");
            return View();
        }

        [HttpPost]
        [Route("/api/transaction/{token}")]
        public async Task<IActionResult> ProcessTransaction(string token)
        {
            try
            {
                Console.WriteLine($"Converting temporary token to permanent: {token}");

                // Step 1: Use token storage to convert temporary token to permanent
                var tokenRequest = new AddMethodRequest
                {
                    CreateAnonymous = true,
                    Temporary = false,
                    Body = new RequestTokenStorage
                    {
                        CustomerData = new PayorDataRequest
                        {
                            CustomerId = 4440 // This should be dynamic based on your needs
                        },
                        EntryPoint = _configService.EntryPoint,
                        PaymentMethod = new ConvertToken
                        {
                            Method = "card",
                            TokenId = token // The temporary token from the embedded component
                        },
                        Source = "web",
                        MethodDescription = "Main card"
                    }
                };

                var tokenResult = await _payabliClient.TokenStorage.AddMethodAsync(tokenRequest);
                Console.WriteLine($"Token storage successful: {tokenResult.IsSuccess}");
                
                var storedMethodId = tokenResult.ResponseData?.ReferenceId;
                if (string.IsNullOrEmpty(storedMethodId))
                {
                    throw new InvalidOperationException("Failed to get stored method ID from token storage response");
                }
                
                Console.WriteLine($"Token stored successfully with ID: {storedMethodId}");

                // Step 2: Process payment using the stored method
                var paymentRequest = new RequestPayment
                {
                    Body = new TransRequestBody
                    {
                        CustomerData = new PayorDataRequest
                        {
                            CustomerId = 4440
                        },
                        EntryPoint = _configService.EntryPoint,
                        Ipaddress = "255.255.255.255", // This should be dynamic based on request
                        PaymentDetails = new PaymentDetail
                        {
                            ServiceFee = 0.0,
                            TotalAmount = 100.0 // This should be dynamic based on your needs
                        },
                        PaymentMethod = new PayMethodStoredMethod
                        {
                            Initiator = "payor",
                            Method = PayMethodStoredMethodMethod.Card,
                            StoredMethodId = storedMethodId,
                            StoredMethodUsageType = "unscheduled"
                        }
                    }
                };

                var paymentResult = await _payabliClient.MoneyIn.GetpaidAsync(paymentRequest);
                Console.WriteLine($"Payment processed successfully: {paymentResult}");

                return Content(
                    "<input type=\"text\" name=\"valid\" value=\"Payment processed successfully!\" aria-invalid=\"false\" readonly>",
                    "text/html"
                );
            }
            catch (PayabliApiApiException ex)
            {
                Console.WriteLine($"API Error processing transaction: {ex.Message}");
                return Content(
                    $"<input type=\"text\" name=\"invalid\" value=\"Payment failed: {ex.Message}\" aria-invalid=\"true\" readonly>",
                    "text/html"
                );
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error processing transaction: {ex.Message}");
                return Content(
                    $"<input type=\"text\" name=\"invalid\" value=\"Error processing transaction: {ex.Message}\" aria-invalid=\"true\" readonly>",
                    "text/html"
                );
            }
        }
    }

    public class CustomerFormModel
    {
        public string Firstname { get; set; } = string.Empty;
        public string Lastname { get; set; } = string.Empty;
        public string Email { get; set; } = string.Empty;
        public string TimeZone { get; set; } = string.Empty;
        public string Address { get; set; } = string.Empty;
        public string City { get; set; } = string.Empty;
        public string State { get; set; } = string.Empty;
        public string Zip { get; set; } = string.Empty;
        public string Country { get; set; } = string.Empty;
        public string? Hvac { get; set; }
        public string? Electrical { get; set; }
        public string Terms { get; set; } = string.Empty;
    }
}
