using PayabliApi;
using DotNetEnv;

var builder = WebApplication.CreateBuilder(args);

// Load environment variables from .env file
Env.Load();

// Add services to the container.
builder.Services.AddControllersWithViews();

// Register PayabliApiClient as a singleton
var apiKey = Environment.GetEnvironmentVariable("PAYABLI_KEY") 
    ?? throw new InvalidOperationException("PAYABLI_KEY environment variable is required");
    
var entryPoint = Environment.GetEnvironmentVariable("PAYABLI_ENTRY") 
    ?? throw new InvalidOperationException("PAYABLI_ENTRY environment variable is required");

builder.Services.AddSingleton<PayabliApiClient>(_ => new PayabliApiClient(apiKey));
builder.Services.AddSingleton<string>(entryPoint); // Store entry point for DI

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
