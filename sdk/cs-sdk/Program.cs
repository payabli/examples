using PayabliApi;
using DotNetEnv;
using PayabliSdkExample.Services;

var builder = WebApplication.CreateBuilder(args);

// Load environment variables from .env file
Env.Load();

// Add services to the container.
builder.Services.AddControllersWithViews();

// Register PayabliApiClient as a singleton
var clientId = Environment.GetEnvironmentVariable("PAYABLI_CLIENT_ID")
    ?? throw new InvalidOperationException("PAYABLI_CLIENT_ID environment variable is required");

var clientSecret = Environment.GetEnvironmentVariable("PAYABLI_CLIENT_SECRET")
    ?? throw new InvalidOperationException("PAYABLI_CLIENT_SECRET environment variable is required");

var entryPoint = Environment.GetEnvironmentVariable("PAYABLI_ENTRY")
    ?? throw new InvalidOperationException("PAYABLI_ENTRY environment variable is required");

var publicToken = Environment.GetEnvironmentVariable("PAYABLI_PUBLIC_TOKEN")
    ?? throw new InvalidOperationException("PAYABLI_PUBLIC_TOKEN environment variable is required");

builder.Services.AddSingleton<PayabliApiClient>(_ => new PayabliApiClient(
    clientId: clientId,
    clientSecret: clientSecret,
    clientOptions: new ClientOptions { BaseUrl = PayabliApiEnvironment.Sandbox }
));
builder.Services.AddSingleton(provider => new ConfigurationService(entryPoint, publicToken));

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

app.UseAuthorization();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();
