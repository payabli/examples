type EmbeddedComponentType = "methodLightBox" | "methodEmbedded" | "vterminal" | "expressCheckout";

interface PayabliEmbeddedComponentFieldPropsSecure {
  label: string;
  placeholder: string;
  floating: boolean;
  size: number;
  row: number;
  order: number;
}

interface PayabliEmbeddedComponentFieldPropsUnsecure
  extends PayabliEmbeddedComponentFieldPropsSecure {
  value: string;
}

type PayabliCountries = "us" | "ca";

interface PayabliEmbeddedComponentCardZipCodeProps
  extends PayabliEmbeddedComponentFieldPropsSecure {
  country: PayabliCountries[];
}

type PayabliEmbeddedComponentCreditCardConfig = {
  enabled: boolean;
  amex: boolean;
  discover: boolean;
  visa: boolean;
  mastercard: boolean;
  jcb: boolean;
  diners: boolean;
  inputs: {
    cardHolderName: PayabliEmbeddedComponentFieldPropsUnsecure;
    cardNumber: PayabliEmbeddedComponentFieldPropsSecure;
    cardExpirationDate: PayabliEmbeddedComponentFieldPropsSecure;
    cardCvv: PayabliEmbeddedComponentFieldPropsSecure;
    cardZipcode: PayabliEmbeddedComponentCardZipCodeProps;
  };
};

type PayabliEmbeddedComponentACHConfig = {
  enabled: boolean;
  checking: boolean;
  savings: boolean;
  inputs: {
    achAccountHolderName: PayabliEmbeddedComponentFieldPropsUnsecure;
    achAccountType: PayabliEmbeddedComponentFieldPropsSecure;
    achRouting: PayabliEmbeddedComponentFieldPropsSecure;
    achAccount: PayabliEmbeddedComponentFieldPropsSecure;
  };
};

type SuccessResponse = {
  responseText: "Success" | "Declined";
  responseData: { resultText: string };
};

type IndividualFieldReadyStatus = boolean;
type FieldReadyStatuses = IndividualFieldReadyStatus[];

export type PayabliEmbeddedComponentOptions = {
  type: EmbeddedComponentType;
  rootContainer: string;
  defaultOpen: "card" | "ach";
  token: string;
  entryPoint: string;
  card: PayabliEmbeddedComponentCreditCardConfig;
  ach: {
  };
  customerData: {
    customerNumber: string;
    firstName: string;
    lastName: string;
    billingEmail: string;
  };

  functionCallBackSuccess: (response: SuccessResponse) => void;
  functionCallBackReady: (data: FieldReadyStatuses) => void;
  functionCallBackError: (errors: Error[]) => void;
};

export type PayabliMethodType = "pay" | "method" | "auth" | "reinit";
