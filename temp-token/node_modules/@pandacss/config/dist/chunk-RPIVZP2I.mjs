// src/resolve-ts-path-pattern.ts
import { posix, sep } from "path";
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
      return mappedId.split(sep).join(posix.sep);
    }
  }
};

export {
  resolveTsPathPattern
};
