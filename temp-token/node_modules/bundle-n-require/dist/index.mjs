var __require = /* @__PURE__ */ ((x) => typeof require !== "undefined" ? require : typeof Proxy !== "undefined" ? new Proxy(x, {
  get: (a, b) => (typeof require !== "undefined" ? require : a)[b]
}) : x)(function(x) {
  if (typeof require !== "undefined")
    return require.apply(this, arguments);
  throw Error('Dynamic require of "' + x + '" is not supported');
});

// src/index.ts
import { build } from "esbuild";
import path from "path";
import fs from "fs";
async function bundleConfigFile(file, cwd, options) {
  const result = await build({
    platform: "node",
    format: "cjs",
    mainFields: ["module", "main"],
    ...options,
    absWorkingDir: cwd,
    entryPoints: [file],
    outfile: "out.js",
    write: false,
    bundle: true,
    sourcemap: false,
    metafile: true
  });
  const { text } = result.outputFiles[0];
  return {
    code: text,
    dependencies: result.metafile ? Object.keys(result.metafile.inputs) : []
  };
}
function loadBundledFile(file, code) {
  const extension = path.extname(file);
  const realFileName = fs.realpathSync.native(file);
  const loader = __require.extensions[extension];
  __require.extensions[extension] = (mod, filename) => {
    if (filename === realFileName) {
      mod._compile(code, filename);
    } else {
      loader(mod, filename);
    }
  };
  delete __require.cache[__require.resolve(file)];
  const raw = __require(file);
  const result = raw.default ?? raw;
  __require.extensions[extension] = loader;
  return result;
}
async function bundleNRequire(file, opts = {}) {
  const { cwd = process.cwd() } = opts;
  const absPath = __require.resolve(file, { paths: [cwd] });
  const bundle = await bundleConfigFile(absPath, cwd);
  try {
    bundle.mod = await loadBundledFile(absPath, bundle.code);
  } catch {
    bundle.mod = __require("node-eval")(bundle.code).default;
  }
  return bundle;
}
export {
  bundleNRequire
};
