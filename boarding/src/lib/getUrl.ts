export function getApiUrlPrefix() {
  const environment = import.meta.env.PAYABLI_ENVIRONMENT;
  
  if (!environment) {
    console.error('Set PAYABLI_ENVIRONMENT in environment variables!');
    return
  }
  switch (environment) {
    case 'production':
      return '';
    case 'qa':
      return '-qa';
    case 'sandbox':
    default:
      return '-sandbox';
  }
}
