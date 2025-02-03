import { BuildOptions } from 'esbuild';

interface BundleNRequireOptions {
    cwd?: string;
    interopDefault?: boolean;
    esbuildOptions?: BuildOptions;
}
interface BundleResult {
    mod: any;
    dependencies: string[];
    code: string;
}
declare function bundleNRequire(file: string, opts?: BundleNRequireOptions): Promise<BundleResult>;

export { type BundleNRequireOptions, type BundleResult, bundleNRequire };
