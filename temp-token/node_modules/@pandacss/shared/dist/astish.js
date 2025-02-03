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

// src/astish.ts
var astish_exports = {};
__export(astish_exports, {
  astish: () => astish
});
module.exports = __toCommonJS(astish_exports);
var newRule = /(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g;
var ruleClean = /\/\*[^]*?\*\/|  +/g;
var ruleNewline = /\n+/g;
var empty = " ";
var astish = (val, tree = [{}]) => {
  if (!val)
    return tree[0];
  let block, left;
  while (block = newRule.exec(val.replace(ruleClean, ""))) {
    if (block[4])
      tree.shift();
    else if (block[3]) {
      left = block[3].replace(ruleNewline, empty).trim();
      tree.unshift(tree[0][left] = tree[0][left] || {});
    } else
      tree[0][block[1]] = block[2].replace(ruleNewline, empty).trim();
  }
  return tree[0];
};
// Annotate the CommonJS export names for ESM import in node:
0 && (module.exports = {
  astish
});
