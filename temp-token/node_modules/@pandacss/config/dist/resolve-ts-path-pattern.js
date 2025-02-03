"use strict";
var __defProp = Object.defineProperty;
var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
var __getOwnPropNames = Object.getOwnPropertyNames;
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
var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

// src/resolve-ts-path-pattern.ts
var resolve_ts_path_pattern_exports = {};
__export(resolve_ts_path_pattern_exports, {
  resolveTsPathPattern: () => resolveTsPathPattern
});
module.exports = __toCommonJS(resolve_ts_path_pattern_exports);
var import_path = require("path");
var resolveTsPathPattern = (pathMappings, moduleSpecifier) => {
  for (const mapping of pathMappings) {
    const match = moduleSpecifier.match(mapping.pattern);
    if (!match) {
      continue;
    }
    for (const pathTemplate of mapping.paths) {
      let starCount = 0;
      const mappedId = pathTemplate.replace(/\*/g, () => {
        const matchIndex = Math.min(++starCount, match.length - 1);
        return match[matchIndex];
      });
      return mappedId.split(import_path.sep).join(import_path.posix.sep);
    }
  }
};
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  resolveTsPathPattern
});
