"use strict";
var __create = Object.create;
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
var __getProtoOf = Object.getPrototypeOf;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __export = (target, all) => {
  for (var name in all)
    __defProp(target, name, { get: all[name], enumerable: true });
};
var __copyProps = (to, from, except, desc) => {
  if (from && typeof from === "object" || typeof from === "function") {
    for (let key of __getOwnPropNames(from))
      if (!__hasOwnProp.call(to, key) && key !== except)
        __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
  }
  return to;
};
var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
  // If the importer is in node compatibility mode or this is not an ESM
  // file that has been converted to a CommonJS file using a Babel-
  // compatible transform (i.e. "__esModule" has not been set), then set
  // "default" to the CommonJS "module.exports" for node compatibility.
  isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
  mod
));
var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

// src/index.ts
var src_exports = {};
__export(src_exports, {
  bundleNRequire: () => bundleNRequire
});
module.exports = __toCommonJS(src_exports);
var import_esbuild = require("esbuild");
var import_path = __toESM(require("path"));
var import_fs = __toESM(require("fs"));
async function bundleConfigFile(file, cwd, options) {
  const result = await (0, import_esbuild.build)({
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
  const extension = import_path.default.extname(file);
  const realFileName = import_fs.default.realpathSync.native(file);
  const loader = require.extensions[extension];
  require.extensions[extension] = (mod, filename) => {
    if (filename === realFileName) {
      mod._compile(code, filename);
    } else {
      loader(mod, filename);
    }
  };
  delete require.cache[require.resolve(file)];
  const raw = require(file);
  const result = raw.default ?? raw;
  require.extensions[extension] = loader;
  return result;
}
async function bundleNRequire(file, opts = {}) {
  const { cwd = process.cwd() } = opts;
  const absPath = require.resolve(file, { paths: [cwd] });
  const bundle = await bundleConfigFile(absPath, cwd);
  try {
    bundle.mod = await loadBundledFile(absPath, bundle.code);
  } catch {
    bundle.mod = require("node-eval")(bundle.code).default;
  }
  return bundle;
}
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  bundleNRequire
});
