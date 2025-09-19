namespace PayabliSdkExample.Services
{
    public class ConfigurationService
    {
        public string EntryPoint { get; }
        public string PublicToken { get; }

        public ConfigurationService(string entryPoint, string publicToken)
        {
            EntryPoint = entryPoint;
            PublicToken = publicToken;
        }
    }
}
