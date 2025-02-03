import { Config } from '@pandacss/types';

type Extendable<T> = T & {
    extend?: T;
};
type ExtendableConfig = Extendable<Config>;
/**
 * Merge all configs into a single config
 */
declare function mergeConfigs(configs: ExtendableConfig[]): any;

export { mergeConfigs };
