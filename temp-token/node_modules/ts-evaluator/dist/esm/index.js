import * as TSModule from 'typescript';
import objectPath from 'object-path';
import path from 'crosspath';
import { createRequire } from 'module';
import { inspect } from 'util';
import color from 'ansi-colors';

const ECMA_GLOBALS = () => {
    /* eslint-disable @typescript-eslint/naming-convention */
    const base = {
        Infinity,
        NaN,
        undefined,
        isNaN,
        parseFloat,
        parseInt,
        decodeURI,
        decodeURIComponent,
        encodeURI,
        encodeURIComponent,
        Array,
        Boolean,
        Date,
        Error,
        EvalError,
        Number,
        Object,
        RangeError,
        ReferenceError,
        RegExp,
        String,
        SyntaxError,
        TypeError,
        URIError,
        JSON,
        Math,
        escape,
        unescape,
        // eslint-disable-next-line no-eval
        eval,
        Function
        /* eslint-enable @typescript-eslint/naming-convention */
    };
    try {
        base.AggregateError = AggregateError;
    }
    catch { }
    try {
        base.FinalizationRegistry = FinalizationRegistry;
    }
    catch { }
    try {
        base.WeakRef = WeakRef;
    }
    catch { }
    try {
        base.BigInt = BigInt;
    }
    catch { }
    try {
        base.Reflect = Reflect;
    }
    catch { }
    try {
        base.WeakMap = WeakMap;
    }
    catch { }
    try {
        base.WeakSet = WeakSet;
    }
    catch { }
    try {
        base.Set = Set;
    }
    catch { }
    try {
        base.Map = Map;
    }
    catch { }
    try {
        base.Uint8Array = Uint8Array;
    }
    catch { }
    try {
        base.BigUint64Array = BigUint64Array;
    }
    catch { }
    try {
        base.BigInt64Array = BigInt64Array;
    }
    catch { }
    try {
        base.Atomics = Atomics;
    }
    catch { }
    try {
        base.SharedArrayBuffer = SharedArrayBuffer;
    }
    catch { }
    try {
        base.WebAssembly = WebAssembly;
    }
    catch { }
    try {
        base.Uint8ClampedArray = Uint8ClampedArray;
    }
    catch { }
    try {
        base.Uint16Array = Uint16Array;
    }
    catch { }
    try {
        base.Uint32Array = Uint32Array;
    }
    catch { }
    try {
        base.Intl = Intl;
    }
    catch { }
    try {
        base.Int8Array = Int8Array;
    }
    catch { }
    try {
        base.Int16Array = Int16Array;
    }
    catch { }
    try {
        base.Int32Array = Int32Array;
    }
    catch { }
    try {
        base.Float32Array = Float32Array;
    }
    catch { }
    try {
        base.Float64Array = Float64Array;
    }
    catch { }
    try {
        base.ArrayBuffer = ArrayBuffer;
    }
    catch { }
    try {
        base.DataView = DataView;
    }
    catch { }
    try {
        base.isFinite = isFinite;
    }
    catch { }
    try {
        base.Promise = Promise;
    }
    catch { }
    try {
        base.Proxy = Proxy;
    }
    catch { }
    try {
        base.Symbol = Symbol;
    }
    catch { }
    return base;
};

/* eslint-disable @typescript-eslint/ban-types */
function mergeDescriptors(a, b, c) {
    const newObj = {};
    const normalizedB = b == null ? {} : b;
    const normalizedC = c == null ? {} : c;
    [a, normalizedB, normalizedC].forEach(item => Object.defineProperties(newObj, Object.getOwnPropertyDescriptors(item)));
    return newObj;
}

/* eslint-disable @typescript-eslint/ban-types */
/**
 * Excludes the properties of B from A
 */
function subtract(a, b) {
    const newA = {};
    Object.getOwnPropertyNames(a).forEach(name => {
        if (!(name in b)) {
            Object.defineProperty(newA, name, Object.getOwnPropertyDescriptor(a, name));
        }
    });
    return newA;
}

// Until import.meta.resolve becomes stable, we'll have to do this instead
const requireModule = createRequire(import.meta.url);

/* eslint-disable @typescript-eslint/naming-convention */
const NODE_CJS_GLOBALS = () => {
    const ecmaGlobals = ECMA_GLOBALS();
    const merged = mergeDescriptors(subtract(global, ecmaGlobals), ecmaGlobals, {
        require: requireModule,
        process,
        __dirname: (fileName) => path.native.normalize(path.native.dirname(fileName)),
        __filename: (fileName) => path.native.normalize(fileName)
    });
    Object.defineProperties(merged, {
        global: {
            get() {
                return merged;
            }
        },
        globalThis: {
            get() {
                return merged;
            }
        }
    });
    return merged;
};

/**
 * Returns an object containing the properties that are relevant to 'requestAnimationFrame' and 'requestIdleCallback'
 */
function rafImplementation(global) {
    let lastTime = 0;
    const _requestAnimationFrame = function requestAnimationFrame(callback) {
        const currTime = new Date().getTime();
        const timeToCall = Math.max(0, 16 - (currTime - lastTime));
        const id = global.setTimeout(function () {
            callback(currTime + timeToCall);
        }, timeToCall);
        lastTime = currTime + timeToCall;
        return id;
    };
    const _cancelAnimationFrame = function cancelAnimationFrame(id) {
        clearTimeout(id);
    };
    return {
        requestAnimationFrame: _requestAnimationFrame,
        cancelAnimationFrame: _cancelAnimationFrame
    };
}

/**
 * The jsdom module is optionally imported on-demand as needed
 */
let jsdomModule;
function loadJsdom(required = false) {
    return (jsdomModule !== null && jsdomModule !== void 0 ? jsdomModule : (jsdomModule = loadModules("evaluate against a browser environment", required, "jsdom")));
}
function loadModules(description, required, moduleSpecifier = description) {
    try {
        return requireModule(moduleSpecifier);
    }
    catch (ex) {
        if (required) {
            throw new ReferenceError(`You must install the peer dependency '${moduleSpecifier}' in order to ${description} with ts-evaluator`);
        }
        return undefined;
    }
}

const BROWSER_GLOBALS = () => {
    const { JSDOM } = loadJsdom(true);
    const { window } = new JSDOM("", { url: "https://example.com" });
    const ecmaGlobals = ECMA_GLOBALS();
    // Add requestAnimationFrame/cancelAnimationFrame if missing
    if (window.requestAnimationFrame == null) {
        const raf = rafImplementation(window);
        Object.defineProperties(window, Object.getOwnPropertyDescriptors(raf));
    }
    // Add all missing Ecma Globals to the JSDOM window
    const missingEcmaGlobals = subtract(ecmaGlobals, window);
    if (Object.keys(missingEcmaGlobals).length > 0) {
        Object.defineProperties(window, Object.getOwnPropertyDescriptors(ecmaGlobals));
    }
    return window;
};

const RETURN_SYMBOL = "[return]";

const BREAK_SYMBOL = "[break]";

const CONTINUE_SYMBOL = "[continue]";

const THIS_SYMBOL = "this";

const SUPER_SYMBOL = "super";

const NODE_ESM_GLOBALS = () => {
    const ecmaGlobals = ECMA_GLOBALS();
    const merged = mergeDescriptors(subtract(global, ecmaGlobals), ecmaGlobals, {
        import: {
            meta: {
                url: (fileName) => {
                    const normalized = path.normalize(fileName);
                    return `file:///${normalized.startsWith(`/`) ? normalized.slice(1) : normalized}`;
                }
            }
        },
        process
    });
    Object.defineProperties(merged, {
        global: {
            get() {
                return merged;
            }
        },
        globalThis: {
            get() {
                return merged;
            }
        }
    });
    return merged;
};

/**
 * Returns true if the given Node is a Declaration
 * Uses an internal non-exposed Typescript helper to decide whether or not the Node is a declaration
 */
function isDeclaration(node, typescript) {
    return typescript.isDeclaration(node);
}
function isNamedDeclaration(node, typescript) {
    if (typescript.isPropertyAccessExpression(node))
        return false;
    return "name" in node && node.name != null;
}

/**
 * Returns true if the given VariableDeclarationList is declared with a 'var' keyword
 */
function isVarDeclaration(declarationList, typescript) {
    return declarationList.flags !== typescript.NodeFlags.Const && declarationList.flags !== typescript.NodeFlags.Let;
}

/**
 * A Base class for EvaluationErrors
 */
class EvaluationError extends Error {
    constructor({ node, environment, message }) {
        super(message);
        Error.captureStackTrace(this, this.constructor);
        this.node = node;
        this.environment = environment;
    }
}
function isEvaluationError(item) {
    return typeof item === "object" && item != null && item instanceof EvaluationError;
}

/**
 * An Error that can be thrown when a moduleSpecifier couldn't be resolved
 */
class ModuleNotFoundError extends EvaluationError {
    constructor({ path, node, environment, message = `Module '${path}' could not be resolved'` }) {
        super({ message, environment, node });
        this.path = path;
    }
}

/**
 * An Error that can be thrown when an unexpected node is encountered
 */
class UnexpectedNodeError extends EvaluationError {
    constructor({ node, environment, typescript, message = `Unexpected Node: '${typescript.SyntaxKind[node.kind]}'` }) {
        super({ message, node, environment });
    }
}

/**
 * Gets the name of the given declaration
 */
function getDeclarationName(options) {
    var _a;
    const { node, evaluate, environment, typescript, throwError } = options;
    const name = typescript.getNameOfDeclaration(node);
    if (name == null)
        return undefined;
    if (typescript.isIdentifier(name)) {
        return name.text;
    }
    else if ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, name)) {
        return name.text;
    }
    else if (typescript.isStringLiteralLike(name)) {
        return name.text;
    }
    else if (typescript.isNumericLiteral(name)) {
        return Number(name.text);
    }
    else if (typescript.isComputedPropertyName(name)) {
        return evaluate.expression(name.expression, options);
    }
    else {
        return throwError(new UnexpectedNodeError({ node: name, environment, typescript }));
    }
}

function getResolvedModuleName(moduleSpecifier, options) {
    const { node, typescript } = options;
    if (!typescript.isExternalModuleNameRelative(moduleSpecifier)) {
        return moduleSpecifier;
    }
    const parentPath = node.getSourceFile().fileName;
    return path.join(path.dirname(parentPath), moduleSpecifier);
}

/**
 * Gets an implementation for the given declaration that lives within a declaration file
 */
function getImplementationForDeclarationWithinDeclarationFile(options) {
    var _a, _b, _c, _d, _e;
    const { node, typescript, throwError, environment } = options;
    const name = getDeclarationName(options);
    if (isEvaluationError(name)) {
        return name;
    }
    if (name == null) {
        return throwError(new UnexpectedNodeError({ node, environment, typescript }));
    }
    // First see if it lives within the lexical environment
    const matchInLexicalEnvironment = getFromLexicalEnvironment(node, options.environment, name);
    // If so, return it
    if (matchInLexicalEnvironment != null && matchInLexicalEnvironment.literal != null) {
        return matchInLexicalEnvironment.literal;
    }
    // Otherwise, expect it to be something that is require'd on demand
    const require = getFromLexicalEnvironment(node, options.environment, "require").literal;
    const moduleDeclaration = typescript.isModuleDeclaration(node)
        ? node
        : findNearestParentNodeOfKind(node, typescript.SyntaxKind.ModuleDeclaration, typescript);
    if (moduleDeclaration == null) {
        return throwError(new UnexpectedNodeError({ node, environment, typescript }));
    }
    const moduleSpecifier = moduleDeclaration.name.text;
    const resolvedModuleSpecifier = getResolvedModuleName(moduleSpecifier, options);
    try {
        // eslint-disable-next-line @typescript-eslint/no-require-imports,@typescript-eslint/no-var-requires
        const module = (_d = (_b = (_a = options.moduleOverrides) === null || _a === void 0 ? void 0 : _a[moduleSpecifier]) !== null && _b !== void 0 ? _b : (_c = options.moduleOverrides) === null || _c === void 0 ? void 0 : _c[resolvedModuleSpecifier]) !== null && _d !== void 0 ? _d : require(resolvedModuleSpecifier);
        return typescript.isModuleDeclaration(node) ? module : (_e = module[name]) !== null && _e !== void 0 ? _e : module;
    }
    catch (ex) {
        if (isEvaluationError(ex))
            return ex;
        else
            return throwError(new ModuleNotFoundError({ node: moduleDeclaration, environment, path: resolvedModuleSpecifier }));
    }
}
function getImplementationFromExternalFile(name, moduleSpecifier, options) {
    var _a, _b, _c, _d, _e, _f;
    const { node, throwError, environment } = options;
    const require = getFromLexicalEnvironment(node, options.environment, "require").literal;
    const resolvedModuleSpecifier = getResolvedModuleName(moduleSpecifier, options);
    try {
        // eslint-disable-next-line @typescript-eslint/no-require-imports,@typescript-eslint/no-var-requires
        const module = (_d = (_b = (_a = options.moduleOverrides) === null || _a === void 0 ? void 0 : _a[moduleSpecifier]) !== null && _b !== void 0 ? _b : (_c = options.moduleOverrides) === null || _c === void 0 ? void 0 : _c[resolvedModuleSpecifier]) !== null && _d !== void 0 ? _d : require(resolvedModuleSpecifier);
        return (_f = (_e = module[name]) !== null && _e !== void 0 ? _e : module.default) !== null && _f !== void 0 ? _f : module;
    }
    catch (ex) {
        if (isEvaluationError(ex))
            return ex;
        else
            return throwError(new ModuleNotFoundError({ node, environment, path: resolvedModuleSpecifier }));
    }
}

/**
 * Finds the nearest parent node of the given kind from the given Node
 */
function findNearestParentNodeOfKind(from, kind, typescript) {
    let currentParent = from;
    while (true) {
        currentParent = currentParent.parent;
        if (currentParent == null)
            return undefined;
        if (currentParent.kind === kind) {
            const combinedNodeFlags = typescript.getCombinedNodeFlags(currentParent);
            const isNamespace = (combinedNodeFlags & typescript.NodeFlags.Namespace) !== 0 || (combinedNodeFlags & typescript.NodeFlags.NestedNamespace) !== 0;
            if (!isNamespace)
                return currentParent;
        }
        if (typescript.isSourceFile(currentParent))
            return undefined;
    }
}
/**
 * Finds the nearest parent node with the given name from the given Node
 */
function findNearestParentNodeWithName(from, name, options, visitedRoots = new WeakSet()) {
    const { typescript } = options;
    let result;
    function visit(nextNode, nestingLayer = 0) {
        var _a, _b, _c, _d, _e;
        if (visitedRoots.has(nextNode))
            return false;
        visitedRoots.add(nextNode);
        if (typescript.isIdentifier(nextNode)) {
            if (nextNode.text === name) {
                result = nextNode;
                return true;
            }
        }
        else if (typescript.isShorthandPropertyAssignment(nextNode)) {
            return false;
        }
        else if (typescript.isPropertyAssignment(nextNode)) {
            return false;
        }
        else if (typescript.isImportDeclaration(nextNode)) {
            if (nextNode.importClause != null) {
                if (nextNode.importClause.name != null && visit(nextNode.importClause.name)) {
                    const moduleSpecifier = nextNode.moduleSpecifier;
                    if (moduleSpecifier != null && typescript.isStringLiteralLike(moduleSpecifier)) {
                        result = getImplementationFromExternalFile(name, moduleSpecifier.text, options);
                        return true;
                    }
                }
                else if (nextNode.importClause.namedBindings != null && visit(nextNode.importClause.namedBindings)) {
                    return true;
                }
            }
            return false;
        }
        else if (typescript.isImportEqualsDeclaration(nextNode)) {
            if (nextNode.name != null && visit(nextNode.name)) {
                if (typescript.isIdentifier(nextNode.moduleReference)) {
                    result = findNearestParentNodeWithName(nextNode.parent, nextNode.moduleReference.text, options, visitedRoots);
                    return result != null;
                }
                else if (typescript.isQualifiedName(nextNode.moduleReference)) {
                    return false;
                }
                else {
                    const moduleSpecifier = nextNode.moduleReference.expression;
                    if (moduleSpecifier != null && typescript.isStringLiteralLike(moduleSpecifier)) {
                        result = getImplementationFromExternalFile(name, moduleSpecifier.text, options);
                        return true;
                    }
                }
            }
            return false;
        }
        else if (typescript.isNamespaceImport(nextNode)) {
            if (visit(nextNode.name)) {
                const moduleSpecifier = (_b = (_a = nextNode.parent) === null || _a === void 0 ? void 0 : _a.parent) === null || _b === void 0 ? void 0 : _b.moduleSpecifier;
                if (moduleSpecifier == null || !typescript.isStringLiteralLike(moduleSpecifier)) {
                    return false;
                }
                result = getImplementationFromExternalFile(name, moduleSpecifier.text, options);
                return true;
            }
        }
        else if (typescript.isNamedImports(nextNode)) {
            for (const importSpecifier of nextNode.elements) {
                if (visit(importSpecifier)) {
                    return true;
                }
            }
        }
        else if (typescript.isImportSpecifier(nextNode)) {
            if (visit(nextNode.name)) {
                const moduleSpecifier = (_e = (_d = (_c = nextNode.parent) === null || _c === void 0 ? void 0 : _c.parent) === null || _d === void 0 ? void 0 : _d.parent) === null || _e === void 0 ? void 0 : _e.moduleSpecifier;
                if (moduleSpecifier == null || !typescript.isStringLiteralLike(moduleSpecifier)) {
                    return false;
                }
                result = getImplementationFromExternalFile(name, moduleSpecifier.text, options);
                return true;
            }
        }
        else if (typescript.isSourceFile(nextNode)) {
            for (const statement of nextNode.statements) {
                if (visit(statement)) {
                    return true;
                }
            }
        }
        else if (typescript.isVariableStatement(nextNode)) {
            for (const declaration of nextNode.declarationList.declarations) {
                if (visit(declaration) && (isVarDeclaration(nextNode.declarationList, typescript) || nestingLayer < 1)) {
                    return true;
                }
            }
        }
        else if (typescript.isBlock(nextNode)) {
            for (const statement of nextNode.statements) {
                if (visit(statement, nestingLayer + 1)) {
                    return true;
                }
            }
        }
        else if (isNamedDeclaration(nextNode, typescript)) {
            if (nextNode.name != null && visit(nextNode.name)) {
                result = nextNode;
                return true;
            }
        }
        return false;
    }
    const suceeded = typescript.findAncestor(from, (nextNode) => visit(nextNode));
    return !suceeded ? undefined : result;
}
function getStatementContext(from, typescript) {
    let currentParent = from;
    while (true) {
        currentParent = currentParent.parent;
        if (currentParent == null)
            return undefined;
        if (isDeclaration(currentParent, typescript) || typescript.isSourceFile(currentParent)) {
            return currentParent;
        }
    }
}

/**
 * Returns true if the provided value is ObjectLike
 *
 * @param value
 * @returns
 */
function isObjectLike(value) {
    return value != null && (typeof value === "function" || typeof value === "object");
}
/**
 * Returns true if the given value can be observed
 *
 * @param value
 * @returns
 */
function canBeObserved(value) {
    return isObjectLike(value);
}

/**
 * Returns true if the given function is either Function.prototype.bind, Function.prototype.call, or Function.prototype.apply
 *
 * @param func
 * @param [environment]
 * @return
 */
function isBindCallApply(func, environment) {
    switch (func) {
        case Function.prototype.bind:
        case Function.prototype.call:
        case Function.prototype.apply:
            return true;
    }
    if (environment != null) {
        const _Function = getFromLexicalEnvironment(undefined, environment, "Function").literal;
        switch (func) {
            case _Function.prototype.bind:
            case _Function.prototype.call:
            case _Function.prototype.apply:
                return true;
        }
    }
    return false;
}

var PolicyTrapKind;
(function (PolicyTrapKind) {
    PolicyTrapKind["GET"] = "__$$_PROXY_GET";
    PolicyTrapKind["APPLY"] = "__$$_PROXY_APPLY";
    PolicyTrapKind["CONSTRUCT"] = "__$$_PROXY_CONSTRUCT";
})(PolicyTrapKind || (PolicyTrapKind = {}));
/**
 * Stringifies the given PolicyTrapKind on the given path
 *
 * @param kind
 * @param path
 * @return
 */
function stringifyPolicyTrapKindOnPath(kind, path) {
    switch (kind) {
        case PolicyTrapKind.GET:
            return `get ${path}`;
        case PolicyTrapKind.APPLY:
            return `${path}(...)`;
        case PolicyTrapKind.CONSTRUCT:
            return `new ${path}(...)`;
    }
}

class EvaluationErrorIntent {
    constructor(intent) {
        this.intent = intent;
    }
    construct(node, options) {
        return this.intent(node, options);
    }
}
function isEvaluationErrorIntent(item) {
    return typeof item === "object" && item != null && item instanceof EvaluationErrorIntent;
}
function maybeThrow(node, options, value) {
    return isEvaluationErrorIntent(value) ? options.throwError(value.construct(node, options)) : value;
}

/* eslint-disable @typescript-eslint/ban-types */
/**
 * Stringifies the given PropertyKey path
 */
function stringifyPath(path) {
    return path.map(part => (typeof part === "symbol" ? part.description : part)).join(".");
}
/**
 * Creates a proxy with hooks to check the given policy
 */
function createPolicyProxy({ hook, item, scope, policy }) {
    /**
     * Creates a trap that captures function invocation
     */
    function createAccessTrap(inputPath, currentItem) {
        const handleHookResult = (result, successCallback) => {
            if (result === false)
                return;
            if (isEvaluationErrorIntent(result) || isEvaluationError(result))
                return result;
            return successCallback();
        };
        return !canBeObserved(currentItem) || isBindCallApply(currentItem)
            ? currentItem
            : new Proxy(currentItem, {
                /**
                 * Constructs a new instance of the given target
                 */
                construct(target, argArray, newTarget) {
                    return handleHookResult(hook({
                        kind: PolicyTrapKind.CONSTRUCT,
                        policy,
                        newTarget,
                        argArray,
                        target,
                        path: stringifyPath(inputPath)
                    }), () => Reflect.construct(target, argArray, newTarget));
                },
                /**
                 * A trap for a function call. Used to create new proxies for methods on the retrieved module objects
                 */
                apply(target, thisArg, argArray = []) {
                    return handleHookResult(hook({
                        kind: PolicyTrapKind.APPLY,
                        policy,
                        thisArg,
                        argArray,
                        target,
                        path: stringifyPath(inputPath)
                    }), () => Reflect.apply(target, thisArg, argArray));
                },
                /**
                 * Gets a trap for 'get' accesses
                 */
                get(target, property, receiver) {
                    const newPath = [...inputPath, property];
                    return handleHookResult(hook({
                        kind: PolicyTrapKind.GET,
                        policy,
                        path: stringifyPath(newPath),
                        target
                    }), () => {
                        const match = Reflect.get(target, property, receiver);
                        const config = Reflect.getOwnPropertyDescriptor(currentItem, property);
                        if (config != null && config.configurable === false && config.writable === false) {
                            return currentItem[property];
                        }
                        return createAccessTrap(newPath, match);
                    });
                }
            });
    }
    return !canBeObserved(item) ? item : createAccessTrap([scope], item);
}

/* eslint-disable @typescript-eslint/naming-convention */
/**
 * A Map between built-in modules and the kind of IO operations their members performs
 * @type {TrapConditionMap<NodeBuiltInsAndGlobals>}
 */
const NETWORK_MAP = {
    "node:http2": "http2",
    http2: {
        connect: {
            [PolicyTrapKind.APPLY]: true
        },
        createSecureServer: {
            [PolicyTrapKind.APPLY]: true
        },
        createServer: {
            [PolicyTrapKind.APPLY]: true
        }
    },
    "node:https": "https",
    https: {
        createServer: {
            [PolicyTrapKind.APPLY]: true
        },
        request: {
            [PolicyTrapKind.APPLY]: true
        },
        get: {
            [PolicyTrapKind.APPLY]: true
        },
        Server: {
            [PolicyTrapKind.CONSTRUCT]: true
        },
        globalAgent: {
            destroy: {
                [PolicyTrapKind.APPLY]: true
            }
        },
        Agent: {
            [PolicyTrapKind.CONSTRUCT]: true
        }
    },
    "node:http": "http",
    http: {
        createServer: {
            [PolicyTrapKind.APPLY]: true
        },
        request: {
            [PolicyTrapKind.APPLY]: true
        },
        get: {
            [PolicyTrapKind.APPLY]: true
        },
        Server: {
            [PolicyTrapKind.CONSTRUCT]: true
        },
        ClientRequest: {
            [PolicyTrapKind.CONSTRUCT]: true
        },
        globalAgent: {
            destroy: {
                [PolicyTrapKind.APPLY]: true
            }
        },
        Agent: {
            [PolicyTrapKind.CONSTRUCT]: true
        }
    },
    "node:dgram": "dgram",
    dgram: {
        createSocket: {
            [PolicyTrapKind.APPLY]: true
        }
    },
    "node:dns": "dns",
    dns: {
        lookup: {
            [PolicyTrapKind.APPLY]: true
        },
        lookupService: {
            [PolicyTrapKind.APPLY]: true
        },
        resolve: {
            [PolicyTrapKind.APPLY]: true
        },
        resolve4: {
            [PolicyTrapKind.APPLY]: true
        },
        resolve6: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveAny: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveCname: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveMx: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveNaptr: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveNs: {
            [PolicyTrapKind.APPLY]: true
        },
        resolvePtr: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveSoa: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveSrv: {
            [PolicyTrapKind.APPLY]: true
        },
        resolveTxt: {
            [PolicyTrapKind.APPLY]: true
        },
        reverse: {
            [PolicyTrapKind.APPLY]: true
        },
        Resolver: {
            [PolicyTrapKind.CONSTRUCT]: true
        }
    },
    "node:net": "net",
    net: {
        createServer: {
            [PolicyTrapKind.APPLY]: true
        },
        createConnection: {
            [PolicyTrapKind.APPLY]: true
        },
        connect: {
            [PolicyTrapKind.APPLY]: true
        },
        Server: {
            [PolicyTrapKind.CONSTRUCT]: true
        }
    },
    "node:tls": "tls",
    tls: {
        createServer: {
            [PolicyTrapKind.APPLY]: true
        },
        createSecureContext: {
            [PolicyTrapKind.APPLY]: true
        },
        connect: {
            [PolicyTrapKind.APPLY]: true
        },
        Server: {
            [PolicyTrapKind.CONSTRUCT]: true
        },
        TLSSocket: {
            [PolicyTrapKind.CONSTRUCT]: true
        }
    }
};

/* eslint-disable @typescript-eslint/naming-convention */
/**
 * A Map between built-in identifiers and the members that produce non-deterministic results.
 */
const NONDETERMINISTIC_MAP = {
    // Any network operation will always be non-deterministic
    ...NETWORK_MAP,
    Math: {
        random: {
            [PolicyTrapKind.APPLY]: true
        }
    },
    Date: {
        now: {
            [PolicyTrapKind.APPLY]: true
        },
        // Dates that receive no arguments are nondeterministic since they care about "now" and will evaluate to a new value for each invocation
        [PolicyTrapKind.CONSTRUCT]: (...args) => args.length === 0 && !(args[0] instanceof Date)
    }
};

/**
 * Returns true if the given item is a TrapCondition
 */
function isTrapCondition(item, condition) {
    // noinspection SuspiciousTypeOfGuard
    return typeof item === typeof condition || typeof item === "function";
}
/**
 * Returns true if the given item is a TrapCondition
 */
function isTrapConditionFunction(item) {
    return typeof item === "function";
}

/**
 * Returns true if the given path represents something that is nondeterministic.
 */
function isTrapConditionMet(map, condition, item) {
    const atoms = item.path.split(".");
    return walkAtoms(map, condition, item, atoms);
}
/**
 * Walks all atoms of the given item path
 */
function walkAtoms(map, matchCondition, item, atoms) {
    const [head, ...tail] = atoms;
    if (head == null)
        return false;
    const mapEntry = map[head];
    // If nothing was matched within the namespace, the trap wasn't matched
    if (mapEntry == null)
        return false;
    if (typeof mapEntry === "string") {
        return walkAtoms(map, matchCondition, item, [mapEntry, ...tail]);
    }
    if (isTrapCondition(mapEntry, matchCondition)) {
        return handleTrapCondition(mapEntry, matchCondition, item);
    }
    else {
        const trapMapMatch = mapEntry[item.kind];
        if (trapMapMatch != null) {
            return handleTrapCondition(trapMapMatch, matchCondition, item);
        }
        else {
            return walkAtoms(mapEntry, matchCondition, item, tail);
        }
    }
}
/**
 * Handles a TrapCondition
 */
function handleTrapCondition(trapCondition, matchCondition, item) {
    // If matching the condition depends on the provided arguments, pass them in
    if (isTrapConditionFunction(trapCondition)) {
        const castItem = item;
        return castItem.argArray != null && trapCondition(...castItem.argArray) === matchCondition;
    }
    // Otherwise, evaluate the truthiness of the condition
    else {
        return trapCondition === matchCondition;
    }
}

/**
 * Returns true if the given path represents something that is nondeterministic.
 */
function isNonDeterministic(item) {
    return isTrapConditionMet(NONDETERMINISTIC_MAP, true, item);
}

/**
 * An Error that can be thrown when a policy is violated
 */
class PolicyError extends EvaluationError {
    constructor({ violation, node, environment, message }) {
        super({ node, environment, message: `[${violation}]: ${message}` });
        this.violation = violation;
    }
}

/**
 * An Error that can be thrown when something nondeterministic is attempted to be evaluated and has been disallowed to be so
 */
class NonDeterministicError extends PolicyError {
    constructor({ operation, node, environment, message = `The operation: '${operation}' is nondeterministic. That is in violation of the policy` }) {
        super({ violation: "deterministic", message, node, environment });
        this.operation = operation;
    }
}

/**
 * A Map between built-in modules and the kind of IO operations their members performs
 * @type {TrapConditionMap<NodeBuiltInsAndGlobals, "read"|"write">}
 */
const IO_MAP = {
    "node:fs": "fs",
    fs: {
        readFile: {
            [PolicyTrapKind.APPLY]: "read"
        },
        readFileSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        readdir: {
            [PolicyTrapKind.APPLY]: "read"
        },
        readdirSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        read: {
            [PolicyTrapKind.APPLY]: "read"
        },
        readSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        exists: {
            [PolicyTrapKind.APPLY]: "read"
        },
        existsSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        access: {
            [PolicyTrapKind.APPLY]: "read"
        },
        accessSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        close: {
            [PolicyTrapKind.APPLY]: "read"
        },
        closeSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        createReadStream: {
            [PolicyTrapKind.APPLY]: "read"
        },
        stat: {
            [PolicyTrapKind.APPLY]: "read"
        },
        statSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        watch: {
            [PolicyTrapKind.APPLY]: "read"
        },
        watchFile: {
            [PolicyTrapKind.APPLY]: "read"
        },
        unwatchFile: {
            [PolicyTrapKind.APPLY]: "read"
        },
        realpath: {
            [PolicyTrapKind.APPLY]: "read"
        },
        realpathSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        fstat: {
            [PolicyTrapKind.APPLY]: "read"
        },
        fstatSync: {
            [PolicyTrapKind.APPLY]: "read"
        },
        createWriteStream: {
            [PolicyTrapKind.APPLY]: "write"
        },
        copyFile: {
            [PolicyTrapKind.APPLY]: "write"
        },
        copyFileSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        unlink: {
            [PolicyTrapKind.APPLY]: "write"
        },
        unlinkSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        rmdir: {
            [PolicyTrapKind.APPLY]: "write"
        },
        rmdirSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        symlink: {
            [PolicyTrapKind.APPLY]: "write"
        },
        symlinkSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        truncate: {
            [PolicyTrapKind.APPLY]: "write"
        },
        truncateSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        utimes: {
            [PolicyTrapKind.APPLY]: "write"
        },
        utimesSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        appendFile: {
            [PolicyTrapKind.APPLY]: "write"
        },
        appendFileSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        write: {
            [PolicyTrapKind.APPLY]: "write"
        },
        writeSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        writeFile: {
            [PolicyTrapKind.APPLY]: "write"
        },
        writeFileSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        chmod: {
            [PolicyTrapKind.APPLY]: "write"
        },
        chmodSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        chown: {
            [PolicyTrapKind.APPLY]: "write"
        },
        chownSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        mkdir: {
            [PolicyTrapKind.APPLY]: "write"
        },
        mkdirSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        rename: {
            [PolicyTrapKind.APPLY]: "write"
        },
        renameSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        futimes: {
            [PolicyTrapKind.APPLY]: "write"
        },
        futimesSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        link: {
            [PolicyTrapKind.APPLY]: "write"
        },
        linkSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        mkdtemp: {
            [PolicyTrapKind.APPLY]: "write"
        },
        open: {
            [PolicyTrapKind.APPLY]: "write"
        },
        openSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fchmod: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fchmodSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fchown: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fchownSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        ftruncate: {
            [PolicyTrapKind.APPLY]: "write"
        },
        ftruncateSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fsync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fsyncSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fdatasync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        fdatasyncSync: {
            [PolicyTrapKind.APPLY]: "write"
        },
        lchmod: {
            [PolicyTrapKind.APPLY]: "write"
        },
        lchmodSync: {
            [PolicyTrapKind.APPLY]: "write"
        }
    }
};

/**
 * Returns true if the given member represents a READ operation from IO
 */
function isIoRead(item) {
    return isTrapConditionMet(IO_MAP, "read", item);
}

/**
 * An Error that can be thrown when an IO operation is attempted to be executed that is in violation of the context policy
 */
class IoError extends PolicyError {
    constructor({ node, environment, kind, message = `${kind} operations are in violation of the policy` }) {
        super({ violation: "io", message, environment, node });
        this.kind = kind;
    }
}

/**
 * Returns true if the given member represents a WRITE operation from IO
 */
function isIoWrite(item) {
    return isTrapConditionMet(IO_MAP, "write", item);
}

/**
 * Returns true if the given item represents a network operation
 */
function isNetworkOperation(item) {
    return isTrapConditionMet(NETWORK_MAP, true, item);
}

/**
 * An Error that can be thrown when a network operation is attempted to be executed that is in violation of the context policy
 */
class NetworkError extends PolicyError {
    constructor({ operation, node, environment, message = `The operation: '${operation}' is performing network activity. That is in violation of the policy` }) {
        super({ violation: "deterministic", message, node, environment });
        this.operation = operation;
    }
}

/* eslint-disable @typescript-eslint/naming-convention */
/**
 * A Map between built-in modules (as well as 'process' and the kind of IO operations their members performs
 */
const PROCESS_MAP = {
    "node:process": "process",
    process: {
        exit: {
            [PolicyTrapKind.APPLY]: "exit"
        }
    },
    // Everything inside child_process is just one big violation of this policy
    "node:child_process": "child_process",
    child_process: {
        [PolicyTrapKind.APPLY]: "spawnChild"
    },
    "node:cluster": "cluster",
    cluster: {
        Worker: {
            [PolicyTrapKind.CONSTRUCT]: "spawnChild"
        }
    }
};

/**
 * Returns true if the given item represents a process operation that exits the process
 */
function isProcessExitOperation(item) {
    return isTrapConditionMet(PROCESS_MAP, "exit", item);
}

/**
 * An Error that can be thrown when a Process operation is attempted to be executed that is in violation of the context policy
 */
class ProcessError extends PolicyError {
    constructor({ kind, node, environment, message = `${kind} operations are in violation of the policy` }) {
        super({ violation: "process", message, node, environment });
        this.kind = kind;
    }
}

/**
 * Returns true if the given item represents a process operation that spawns a child
 */
function isProcessSpawnChildOperation(item) {
    return isTrapConditionMet(PROCESS_MAP, "spawnChild", item);
}

/* eslint-disable @typescript-eslint/naming-convention */
/**
 * A Map between built-in modules (as well as 'console' and the operations that print to console
 */
const CONSOLE_MAP = {
    "node:console": "console",
    console: {
        [PolicyTrapKind.APPLY]: true
    }
};

/**
 * Returns true if the given item represents an operation that prints to console
 */
function isConsoleOperation(item) {
    return isTrapConditionMet(CONSOLE_MAP, true, item);
}

/**
 * Creates an environment that provide hooks into policy checks
 */
function createSanitizedEnvironment({ policy, env }) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const hook = (item) => {
        if (!policy.console && isConsoleOperation(item)) {
            return false;
        }
        if (!policy.io.read && isIoRead(item)) {
            return new EvaluationErrorIntent((node, options) => new IoError({ ...options, node, kind: "read" }));
        }
        if (!policy.io.write && isIoWrite(item)) {
            return new EvaluationErrorIntent((node, options) => new IoError({ ...options, node, kind: "write" }));
        }
        if (!policy.process.exit && isProcessExitOperation(item)) {
            return new EvaluationErrorIntent((node, options) => new ProcessError({ ...options, node, kind: "exit" }));
        }
        if (!policy.process.exit && isProcessSpawnChildOperation(item)) {
            return new EvaluationErrorIntent((node, options) => new ProcessError({ ...options, node, kind: "spawnChild" }));
        }
        if (!policy.network && isNetworkOperation(item)) {
            return new EvaluationErrorIntent((node, options) => new NetworkError({ ...options, node, operation: stringifyPolicyTrapKindOnPath(item.kind, item.path) }));
        }
        if (policy.deterministic && isNonDeterministic(item)) {
            return new EvaluationErrorIntent((node, options) => new NonDeterministicError({ ...options, node, operation: stringifyPolicyTrapKindOnPath(item.kind, item.path) }));
        }
        return true;
    };
    const descriptors = Object.entries(Object.getOwnPropertyDescriptors(env));
    const gettersAndSetters = Object.assign({}, ...descriptors.filter(([, descriptor]) => !("value" in descriptor)).map(([name, descriptor]) => ({ [name]: descriptor })));
    const values = Object.assign({}, ...descriptors
        .filter(([, descriptor]) => "value" in descriptor)
        .map(([name, descriptor]) => ({
        [name]: name === "require"
            ? new Proxy(descriptor.value, {
                /**
                 * A trap for a function call. Used to create new proxies for methods on the retrieved module objects
                 */
                apply(target, thisArg, argArray = []) {
                    const [moduleName] = argArray;
                    return createPolicyProxy({
                        policy,
                        item: Reflect.apply(target, thisArg, argArray),
                        scope: moduleName,
                        hook
                    });
                }
            })
            : createPolicyProxy({
                policy,
                item: descriptor.value,
                scope: name,
                hook
            })
    })));
    return Object.defineProperties(values, {
        ...gettersAndSetters
    });
}

/**
 * Gets a value from a Lexical Environment
 */
function getRelevantDictFromLexicalEnvironment(env, path) {
    const [firstBinding] = path.split(".");
    if (objectPath.has(env.env, firstBinding))
        return env.env;
    if (env.parentEnv != null)
        return getRelevantDictFromLexicalEnvironment(env.parentEnv, path);
    return undefined;
}
/**
 * Gets the EnvironmentPresetKind for the given LexicalEnvironment
 */
function getPresetForLexicalEnvironment(env) {
    if (env.preset != null)
        return env.preset;
    else if (env.parentEnv != null)
        return getPresetForLexicalEnvironment(env.parentEnv);
    else
        return "NONE";
}
function findLexicalEnvironmentInSameContext(from, node, typescript) {
    const startingNodeContext = getStatementContext(from.startingNode, typescript);
    const nodeContext = getStatementContext(node, typescript);
    if ((startingNodeContext === null || startingNodeContext === void 0 ? void 0 : startingNodeContext.pos) === (nodeContext === null || nodeContext === void 0 ? void 0 : nodeContext.pos)) {
        return from;
    }
    if (from.parentEnv == null) {
        return undefined;
    }
    return findLexicalEnvironmentInSameContext(from.parentEnv, node, typescript);
}
/**
 * Gets a value from a Lexical Environment
 */
function getFromLexicalEnvironment(node, env, path) {
    const [firstBinding] = path.split(".");
    if (objectPath.has(env.env, firstBinding)) {
        const literal = objectPath.get(env.env, path);
        switch (path) {
            // If we're in a Node environment, the "__dirname" and "__filename" meta-properties should report the current directory or file of the SourceFile and not the parent process
            case "__dirname":
            case "__filename": {
                const preset = getPresetForLexicalEnvironment(env);
                return (preset === "NODE" || preset === "NODE_CJS") && typeof literal === "function" && node != null ? { literal: literal(node.getSourceFile().fileName) } : { literal };
            }
            case "import.meta": {
                const preset = getPresetForLexicalEnvironment(env);
                return (preset === "NODE_ESM" || preset === "BROWSER" || preset === "ECMA") &&
                    typeof literal === "object" &&
                    literal != null &&
                    typeof literal.url === "function" &&
                    node != null
                    ? { literal: { url: literal.url(node.getSourceFile().fileName) } }
                    : { literal };
            }
            default:
                return { literal };
        }
    }
    if (env.parentEnv != null)
        return getFromLexicalEnvironment(node, env.parentEnv, path);
    return undefined;
}
/**
 * Returns true if the given lexical environment contains a value on the given path that equals the given literal
 */
function pathInLexicalEnvironmentEquals(node, env, equals, ...matchPaths) {
    return matchPaths.some(path => {
        const match = getFromLexicalEnvironment(node, env, path);
        return match == null ? false : match.literal === equals;
    });
}
/**
 * Returns true if the given value represents an internal symbol
 */
function isInternalSymbol(value) {
    switch (value) {
        case RETURN_SYMBOL:
        case BREAK_SYMBOL:
        case CONTINUE_SYMBOL:
        case THIS_SYMBOL:
        case SUPER_SYMBOL:
            return true;
        default:
            return false;
    }
}
/**
 * Gets a value from a Lexical Environment
 */
function setInLexicalEnvironment({ environment, path, value, reporting, node, newBinding = false }) {
    const [firstBinding] = path.split(".");
    if (objectPath.has(environment.env, firstBinding) || newBinding || environment.parentEnv == null) {
        // If the value didn't change, do no more
        if (objectPath.has(environment.env, path) && objectPath.get(environment.env, path) === value)
            return;
        // Otherwise, mutate it
        objectPath.set(environment.env, path, value);
        // Inform reporting hooks if any is given
        if (reporting.reportBindings != null && !isInternalSymbol(path)) {
            reporting.reportBindings({ path, value, node });
        }
    }
    else {
        let currentParentEnv = environment.parentEnv;
        while (currentParentEnv != null) {
            if (objectPath.has(currentParentEnv.env, firstBinding)) {
                // If the value didn't change, do no more
                if (objectPath.has(currentParentEnv.env, path) && objectPath.get(currentParentEnv.env, path) === value)
                    return;
                // Otherwise, mutate it
                objectPath.set(currentParentEnv.env, path, value);
                // Inform reporting hooks if any is given
                if (reporting.reportBindings != null && !isInternalSymbol(path)) {
                    reporting.reportBindings({ path, value, node });
                }
                return;
            }
            else {
                if (currentParentEnv.parentEnv == null) {
                    // Otherwise, mutate it
                    objectPath.set(currentParentEnv.env, path, value);
                    // Inform reporting hooks if any is given
                    if (reporting.reportBindings != null && !isInternalSymbol(path)) {
                        reporting.reportBindings({ path, value, node });
                    }
                }
                else {
                    currentParentEnv = currentParentEnv.parentEnv;
                }
            }
        }
    }
}
/**
 * Creates a Lexical Environment
 */
function createLexicalEnvironment({ inputEnvironment: { extra, preset }, startingNode, policy, }) {
    let env;
    switch (preset) {
        case "NONE":
            env = mergeDescriptors(extra);
            break;
        case "ECMA":
            env = mergeDescriptors(ECMA_GLOBALS(), extra);
            break;
        case "NODE":
        case "NODE_CJS":
            env = mergeDescriptors(NODE_CJS_GLOBALS(), extra);
            break;
        case "NODE_ESM":
            env = mergeDescriptors(NODE_ESM_GLOBALS(), extra);
            break;
        case "BROWSER":
            env = mergeDescriptors(BROWSER_GLOBALS(), extra);
            break;
        default:
            env = {};
            break;
    }
    return {
        parentEnv: undefined,
        preset,
        startingNode,
        env: createSanitizedEnvironment({
            policy,
            env
        })
    };
}

/**
 * Returns true if the given node is a BooleanLiteral
 */
function isBooleanLiteral(node, typescript) {
    return node.kind === typescript.SyntaxKind.TrueKeyword || node.kind === typescript.SyntaxKind.FalseKeyword;
}

/**
 * Returns true if the given node is a NullLiteral
 */
function isNullLiteral(node, typescript) {
    return node.kind === typescript.SyntaxKind.NullKeyword;
}

/**
 * This is a tiny function that avoids the costs of building up an evaluation environment
 * for the interpreter. If the node is a simple literal, it will return its' value.
 */
function evaluateSimpleLiteral(node, typescript) {
    var _a;
    if (typescript.isStringLiteralLike(node))
        return { success: true, value: node.text };
    else if (isBooleanLiteral(node, typescript))
        return { success: true, value: node.kind === typescript.SyntaxKind.TrueKeyword };
    else if (typescript.isRegularExpressionLiteral(node))
        return { success: true, value: new Function(`return ${node.text}`)() };
    else if (typescript.isNumericLiteral(node))
        return { success: true, value: Number(node.text) };
    else if ((_a = typescript.isBigIntLiteral) === null || _a === void 0 ? void 0 : _a.call(typescript, node))
        return { success: true, value: BigInt(node.text) };
    else if (typescript.isIdentifier(node) && node.text === "Infinity")
        return { success: true, value: Infinity };
    else if (typescript.isIdentifier(node) && node.text === "NaN")
        return { success: true, value: NaN };
    else if (typescript.isIdentifier(node) && node.text === "null")
        return { success: true, value: null };
    else if (typescript.isIdentifier(node) && node.text === "undefined")
        return { success: true, value: undefined };
    else if (isNullLiteral(node, typescript))
        return { success: true, value: null };
    else
        return { success: false };
}

/**
 * An Error that can be thrown when the maximum amount of operations dictated by the policy is exceeded
 */
class MaxOpsExceededError extends PolicyError {
    constructor({ ops, node, environment, message = `Maximum ops exceeded: ${ops}` }) {
        super({ violation: "maxOps", message, node, environment });
        this.ops = ops;
    }
}

/**
 * Evaluates, or attempts to evaluate, a VariableDeclaration
 */
function evaluateVariableDeclaration(options, initializer) {
    const { node, environment, evaluate, stack, typescript, throwError, getCurrentError } = options;
    const initializerResult = initializer != null
        ? initializer
        : node.initializer == null
            ? // A VariableDeclaration with no initializer is implicitly bound to 'undefined'
                undefined
            : evaluate.expression(node.initializer, options);
    if (getCurrentError() != null) {
        return;
    }
    // There's no way of destructuring a nullish value
    if (initializerResult == null && !typescript.isIdentifier(node.name)) {
        return throwError(new EvaluationError({ node, environment }));
    }
    // Evaluate the binding name
    evaluate.nodeWithArgument(node.name, initializerResult, options);
    if (getCurrentError() != null) {
        return;
    }
    stack.push(initializerResult);
}

/**
 * Returns true if the given node is a ThisExpression
 */
function isThisExpression(node, typescript) {
    return node.kind === typescript.SyntaxKind.ThisKeyword;
}

/**
 * Returns true if the given node is a SuperExpression
 */
function isSuperExpression(node, typescript) {
    return node.kind === typescript.SyntaxKind.SuperKeyword;
}

/**
 * Gets the path to "dot" into an object with based on the node. For example, if the node is a simple identifier, say, 'foo', the dot path is simply "foo".
 * And, if it is a PropertyAccessExpression, that path may be "console.log" for example
 */
function getDotPathFromNode(options) {
    var _a, _b;
    const { node, evaluate, typescript } = options;
    if (typescript.isIdentifier(node)) {
        return node.text;
    }
    else if ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node)) {
        return node.text;
    }
    else if (isThisExpression(node, typescript)) {
        return THIS_SYMBOL;
    }
    else if (isSuperExpression(node, typescript)) {
        return SUPER_SYMBOL;
    }
    else if (typescript.isParenthesizedExpression(node)) {
        return getDotPathFromNode({ ...options, node: node.expression });
    }
    else if (((_b = typescript.isTypeAssertionExpression) === null || _b === void 0 ? void 0 : _b.call(typescript, node)) ||
        (!("isTypeAssertionExpression" in typescript) && typescript.isTypeAssertion(node))) {
        return getDotPathFromNode({ ...options, node: node.expression });
    }
    else if (typescript.isPropertyAccessExpression(node)) {
        let leftHand = getDotPathFromNode({ ...options, node: node.expression });
        if (leftHand == null)
            leftHand = evaluate.expression(node.expression, options);
        let rightHand = getDotPathFromNode({ ...options, node: node.name });
        if (rightHand == null)
            rightHand = evaluate.expression(node.name, options);
        if (leftHand == null || rightHand == null)
            return undefined;
        return `${leftHand}.${rightHand}`;
    }
    else if (typescript.isElementAccessExpression(node)) {
        let leftHand = getDotPathFromNode({ ...options, node: node.expression });
        if (leftHand == null)
            leftHand = evaluate.expression(node.expression, options);
        const rightHand = evaluate.expression(node.argumentExpression, options);
        if (leftHand == null || rightHand == null)
            return undefined;
        return `${leftHand}.${rightHand}`;
    }
    else if (typescript.isFunctionDeclaration(node)) {
        if (node.name == null)
            return undefined;
        return node.name.text;
    }
    return undefined;
}

/**
 * An Error that can be thrown when an undefined leftValue is encountered
 */
class UndefinedLeftValueError extends EvaluationError {
    constructor({ node, environment, message = `'No leftValue could be determined'` }) {
        super({ message, environment, node });
    }
}

function getInnerNode(node, typescript) {
    var _a;
    if (typescript.isParenthesizedExpression(node))
        return getInnerNode(node.expression, typescript);
    else if (typescript.isAsExpression(node))
        return getInnerNode(node.expression, typescript);
    else if (((_a = typescript.isTypeAssertionExpression) === null || _a === void 0 ? void 0 : _a.call(typescript, node)) ||
        (!("isTypeAssertionExpression" in typescript) && typescript.isTypeAssertion(node))) {
        return getInnerNode(node.expression, typescript);
    }
    else {
        return node;
    }
}

function isTypescriptNode(node) {
    return node != null && typeof node === "object" && "kind" in node && "flags" in node && "pos" in node && "end" in node;
}

/**
 * Evaluates, or attempts to evaluate, a BinaryExpression
 */
function evaluateBinaryExpression(options) {
    var _a;
    const { node, environment, evaluate, logger, throwError, typeChecker, typescript, getCurrentError } = options;
    const leftValue = evaluate.expression(node.left, options);
    if (getCurrentError() != null) {
        return;
    }
    const rightValue = evaluate.expression(node.right, options);
    if (getCurrentError() != null) {
        return;
    }
    const leftIdentifier = getDotPathFromNode({ ...options, node: node.left });
    const operator = node.operatorToken.kind;
    switch (operator) {
        case typescript.SyntaxKind.AmpersandToken: {
            return leftValue & rightValue;
        }
        case typescript.SyntaxKind.AmpersandAmpersandToken: {
            // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
            return leftValue && rightValue;
        }
        case typescript.SyntaxKind.AmpersandEqualsToken:
        case typescript.SyntaxKind.CaretEqualsToken:
        case typescript.SyntaxKind.BarEqualsToken:
        case typescript.SyntaxKind.MinusEqualsToken:
        case typescript.SyntaxKind.PlusEqualsToken:
        case typescript.SyntaxKind.PercentEqualsToken:
        case typescript.SyntaxKind.SlashEqualsToken:
        case typescript.SyntaxKind.AsteriskEqualsToken:
        case typescript.SyntaxKind.AsteriskAsteriskEqualsToken:
        case typescript.SyntaxKind.LessThanLessThanEqualsToken:
        case typescript.SyntaxKind.GreaterThanGreaterThanEqualsToken:
        case typescript.SyntaxKind.GreaterThanGreaterThanGreaterThanEqualsToken:
        case typescript.SyntaxKind.QuestionQuestionEqualsToken:
        case typescript.SyntaxKind.BarBarEqualsToken:
        case typescript.SyntaxKind.AmpersandAmpersandEqualsToken: {
            // There's nothing in the engine restricting you from applying this kind of arithmetic operation on non-numeric data types
            let computedValue = leftValue;
            switch (operator) {
                case typescript.SyntaxKind.AmpersandEqualsToken:
                    computedValue &= rightValue;
                    break;
                case typescript.SyntaxKind.CaretEqualsToken:
                    computedValue ^= rightValue;
                    break;
                case typescript.SyntaxKind.BarEqualsToken:
                    computedValue |= rightValue;
                    break;
                case typescript.SyntaxKind.AsteriskEqualsToken:
                    computedValue *= rightValue;
                    break;
                case typescript.SyntaxKind.AsteriskAsteriskEqualsToken:
                    computedValue **= rightValue;
                    break;
                case typescript.SyntaxKind.LessThanLessThanEqualsToken:
                    computedValue <<= rightValue;
                    break;
                case typescript.SyntaxKind.GreaterThanGreaterThanEqualsToken:
                    computedValue >>= rightValue;
                    break;
                case typescript.SyntaxKind.GreaterThanGreaterThanGreaterThanEqualsToken:
                    computedValue >>>= rightValue;
                    break;
                case typescript.SyntaxKind.MinusEqualsToken:
                    computedValue -= rightValue;
                    break;
                case typescript.SyntaxKind.PlusEqualsToken:
                    computedValue += rightValue;
                    break;
                case typescript.SyntaxKind.PercentEqualsToken:
                    computedValue %= rightValue;
                    break;
                case typescript.SyntaxKind.SlashEqualsToken:
                    computedValue /= rightValue;
                    break;
                case typescript.SyntaxKind.QuestionQuestionEqualsToken:
                    computedValue = leftValue == null ? rightValue : leftValue;
                    break;
                case typescript.SyntaxKind.BarBarEqualsToken:
                    if (!leftValue) {
                        computedValue = rightValue;
                    }
                    break;
                case typescript.SyntaxKind.AmpersandAmpersandEqualsToken:
                    if (leftValue) {
                        computedValue = rightValue;
                    }
                    break;
            }
            // Update to the left-value within the environment if it exists there and has been updated
            if (leftIdentifier != null) {
                setInLexicalEnvironment({ ...options, path: leftIdentifier, value: computedValue });
            }
            // Return the computed value
            return computedValue;
        }
        case typescript.SyntaxKind.AsteriskToken: {
            return leftValue * rightValue;
        }
        case typescript.SyntaxKind.AsteriskAsteriskToken: {
            return leftValue ** rightValue;
        }
        case typescript.SyntaxKind.BarToken: {
            return leftValue | rightValue;
        }
        case typescript.SyntaxKind.BarBarToken: {
            // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
            return leftValue || rightValue;
        }
        case typescript.SyntaxKind.CaretToken: {
            return leftValue ^ rightValue;
        }
        case typescript.SyntaxKind.CommaToken: {
            return rightValue;
        }
        case typescript.SyntaxKind.MinusToken:
            return leftValue - rightValue;
        case typescript.SyntaxKind.PlusToken:
            logger.logResult(leftValue + rightValue, "BinaryExpression (PlusToken)");
            return leftValue + rightValue;
        case typescript.SyntaxKind.PercentToken:
            return leftValue % rightValue;
        case typescript.SyntaxKind.SlashToken:
            return leftValue / rightValue;
        case typescript.SyntaxKind.EqualsToken: {
            // Update to the left-value within the environment if it exists there and has been updated
            if (leftIdentifier != null) {
                const innerLeftIdentifier = getInnerNode(node.left, typescript);
                const leftIdentifierSymbol = typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getSymbolAtLocation(innerLeftIdentifier);
                let leftIdentifierValueDeclaration = leftIdentifierSymbol === null || leftIdentifierSymbol === void 0 ? void 0 : leftIdentifierSymbol.valueDeclaration;
                // If we don't have a typechecker to work it, try parsing the SourceFile in order to locate the declaration
                if (leftIdentifierValueDeclaration == null && typeChecker == null && typescript.isIdentifier(innerLeftIdentifier)) {
                    const result = findNearestParentNodeWithName(innerLeftIdentifier.parent, innerLeftIdentifier.text, options);
                    if (isTypescriptNode(result)) {
                        leftIdentifierValueDeclaration = result;
                    }
                }
                const bestLexicalEnvironment = leftIdentifierValueDeclaration == null ? environment : (_a = findLexicalEnvironmentInSameContext(environment, leftIdentifierValueDeclaration, typescript)) !== null && _a !== void 0 ? _a : environment;
                setInLexicalEnvironment({ ...options, environment: bestLexicalEnvironment, path: leftIdentifier, value: rightValue });
                logger.logBinding(leftIdentifier, rightValue, "Assignment");
            }
            else {
                return throwError(new UndefinedLeftValueError({ node: node.left, environment }));
            }
            // The return value of an assignment is always the assigned value
            return rightValue;
        }
        case typescript.SyntaxKind.EqualsEqualsToken: {
            // eslint-disable-next-line eqeqeq
            return leftValue == rightValue;
        }
        case typescript.SyntaxKind.EqualsEqualsEqualsToken: {
            return leftValue === rightValue;
        }
        case typescript.SyntaxKind.ExclamationEqualsToken: {
            // eslint-disable-next-line eqeqeq
            return leftValue != rightValue;
        }
        case typescript.SyntaxKind.ExclamationEqualsEqualsToken: {
            return leftValue !== rightValue;
        }
        case typescript.SyntaxKind.GreaterThanToken:
            return leftValue > rightValue;
        case typescript.SyntaxKind.GreaterThanEqualsToken:
            return leftValue >= rightValue;
        case typescript.SyntaxKind.LessThanToken:
            return leftValue < rightValue;
        case typescript.SyntaxKind.LessThanEqualsToken:
            return leftValue <= rightValue;
        case typescript.SyntaxKind.InKeyword: {
            return leftValue in rightValue;
        }
        // Nullish coalescing (A ?? B)
        case typescript.SyntaxKind.QuestionQuestionToken:
            return leftValue != null ? leftValue : rightValue;
        case typescript.SyntaxKind.InstanceOfKeyword: {
            return leftValue instanceof rightValue;
        }
    }
    // Throw if the operator couldn't be handled
    return throwError(new UnexpectedNodeError({ node: node.operatorToken, typescript, environment }));
}

// tslint:disable:no-any
var LiteralFlagKind;
(function (LiteralFlagKind) {
    LiteralFlagKind[LiteralFlagKind["CALL"] = 0] = "CALL";
})(LiteralFlagKind || (LiteralFlagKind = {}));
const LAZY_CALL_FLAG = "___lazyCallFlag";
/**
 * Returns true if the given literal is a lazy call
 */
function isLazyCall(literal) {
    return literal != null && typeof literal === "object" && LAZY_CALL_FLAG in literal;
}
/**
 * Stringifies the given literal
 */
function stringifyLiteral(literal) {
    if (literal === undefined)
        return "undefined";
    else if (literal === null)
        return "null";
    else if (typeof literal === "string")
        return `"${literal}"`;
    return literal.toString();
}

/**
 * An Error that can be thrown when a value is attempted to be called, but isn't callable
 */
class NotCallableError extends EvaluationError {
    constructor({ value, node, environment, message = `${stringifyLiteral(value)} is not a function'` }) {
        super({ message, environment, node });
        this.value = value;
    }
}

/**
 * Returns true if the given expression contains a 'super' keyword
 */
function expressionContainsSuperKeyword(expression, typescript) {
    var _a;
    if (isSuperExpression(expression, typescript))
        return true;
    else if (typescript.isPropertyAccessExpression(expression)) {
        return expressionContainsSuperKeyword(expression.expression, typescript) || expressionContainsSuperKeyword(expression.name, typescript);
    }
    else if (typescript.isElementAccessExpression(expression)) {
        return expressionContainsSuperKeyword(expression.expression, typescript) || expressionContainsSuperKeyword(expression.argumentExpression, typescript);
    }
    else if (typescript.isParenthesizedExpression(expression))
        return expressionContainsSuperKeyword(expression.expression, typescript);
    else if (typescript.isAsExpression(expression))
        return expressionContainsSuperKeyword(expression.expression, typescript);
    else if (((_a = typescript.isTypeAssertionExpression) === null || _a === void 0 ? void 0 : _a.call(typescript, expression)) ||
        (!("isTypeAssertionExpression" in typescript) && typescript.isTypeAssertion(expression))) {
        return expressionContainsSuperKeyword(expression.expression, typescript);
    }
    else {
        return false;
    }
}

/**
 * Evaluates, or attempts to evaluate, a CallExpression
 */
function evaluateCallExpression(options) {
    const { node, environment, evaluate, throwError, typescript, logger, getCurrentError } = options;
    const evaluatedArgs = [];
    for (let i = 0; i < node.arguments.length; i++) {
        evaluatedArgs[i] = evaluate.expression(node.arguments[i], options);
        if (getCurrentError() != null) {
            return;
        }
    }
    // Evaluate the expression
    const expressionResult = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    if (isLazyCall(expressionResult)) {
        const currentThisBinding = expressionContainsSuperKeyword(node.expression, typescript) ? getFromLexicalEnvironment(node, environment, THIS_SYMBOL) : undefined;
        const value = expressionResult.invoke(currentThisBinding != null ? currentThisBinding.literal : undefined, ...evaluatedArgs);
        if (getCurrentError() != null) {
            return;
        }
        logger.logResult(value, "CallExpression");
        return value;
    }
    // Otherwise, assume that the expression still needs calling
    else {
        // Unless optional chaining is being used, throw a NotCallableError
        if (node.questionDotToken == null && typeof expressionResult !== "function") {
            return throwError(new NotCallableError({ value: expressionResult, node: node.expression, environment }));
        }
        const value = typeof expressionResult !== "function" ? undefined : maybeThrow(node, options, expressionResult(...evaluatedArgs));
        if (getCurrentError() != null) {
            return;
        }
        logger.logResult(value, "CallExpression");
        return value;
    }
}

/**
 * Evaluates, or attempts to evaluate, a ParenthesizedExpression
 */
function evaluateParenthesizedExpression({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Clones the given LexicalEnvironment
 */
function cloneLexicalEnvironment(environment, startingNode) {
    return {
        parentEnv: environment,
        startingNode,
        env: {}
    };
}

/**
 * Returns true if the given Node has the given kind of Modifier
 */
function hasModifier(node, modifier) {
    const modifiers = Array.isArray(node) ? node : "modifiers" in node && Array.isArray(node.modifiers) ? node.modifiers : undefined;
    return modifiers != null && modifiers.some(m => m.kind === modifier);
}

/**
 * Evaluates, or attempts to evaluate, a NodeArray of ParameterDeclarations
 */
function evaluateParameterDeclarations(options, boundArguments, context) {
    const { node, evaluate, environment, typescript, getCurrentError } = options;
    // 'this' is a special parameter which is removed from the emitted results
    const parameters = node.filter(param => !(typescript.isIdentifier(param.name) && param.name.text === "this"));
    for (let i = 0; i < parameters.length; i++) {
        const parameter = parameters[i];
        // It it is a spread element, it should receive all arguments from the current index.
        if (parameter.dotDotDotToken != null) {
            evaluate.nodeWithArgument(parameter, boundArguments.slice(i), options);
            if (getCurrentError() != null) {
                return;
            }
            // Spread elements must always be the last parameter
            break;
        }
        else {
            evaluate.nodeWithArgument(parameter, boundArguments[i], options);
            if (getCurrentError() != null) {
                return;
            }
            // If a context is given, and if a [public|protected|private] keyword is in front of the parameter, the initialized value should be
            // set on the context as an instance property
            if (context != null &&
                typescript.isIdentifier(parameter.name) &&
                (hasModifier(parameter, typescript.SyntaxKind.PublicKeyword) ||
                    hasModifier(parameter, typescript.SyntaxKind.ProtectedKeyword) ||
                    hasModifier(parameter, typescript.SyntaxKind.PrivateKeyword))) {
                const value = getFromLexicalEnvironment(parameter, environment, parameter.name.text);
                if (value != null) {
                    context[parameter.name.text] = value.literal;
                }
            }
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, an ArrowFunction
 */
function evaluateArrowFunctionExpression(options) {
    const { node, environment, evaluate, stack, typescript, getCurrentError } = options;
    const arrowFunctionExpression = hasModifier(node, typescript.SyntaxKind.AsyncKeyword)
        ? async (...args) => {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...options,
                node: node.parameters,
                environment: localLexicalEnvironment
            }, args);
            if (getCurrentError() != null) {
                return;
            }
            // If the body is a block, evaluate it as a statement
            if (typescript.isBlock(node.body)) {
                evaluate.statement(node.body, nextOptions);
                if (getCurrentError() != null) {
                    return;
                }
                // If a 'return' has occurred within the block, pop the Stack and return that value
                if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                    return stack.pop();
                }
                // Otherwise, return 'undefined'. Nothing is returned from the function
                else
                    return undefined;
            }
            // Otherwise, the body is itself an expression
            else {
                return evaluate.expression(node.body, nextOptions);
            }
        }
        : (...args) => {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...options,
                node: node.parameters,
                environment: localLexicalEnvironment
            }, args);
            if (getCurrentError() != null) {
                return;
            }
            // If the body is a block, evaluate it as a statement
            if (typescript.isBlock(node.body)) {
                evaluate.statement(node.body, nextOptions);
                if (getCurrentError() != null) {
                    return;
                }
                // If a 'return' has occurred within the block, pop the Stack and return that value
                if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                    return stack.pop();
                }
                // Otherwise, return 'undefined'. Nothing is returned from the function
                else
                    return undefined;
            }
            // Otherwise, the body is itself an expression
            else {
                return evaluate.expression(node.body, nextOptions);
            }
        };
    arrowFunctionExpression.toString = () => `[Function: anonymous]`;
    // Make sure to use the Function that is contained within the Realm. Otherwise, 'instanceof' checks may fail
    // since this particular function comes from the executing context.
    Object.setPrototypeOf(arrowFunctionExpression, getFromLexicalEnvironment(node, environment, "Function").literal);
    return arrowFunctionExpression;
}

/**
 * Evaluates, or attempts to evaluate, a StringLiteralLike
 */
function evaluateStringLiteral({ node }) {
    return node.text;
}

/**
 * Evaluates, or attempts to evaluate, a NumericLiteral
 */
function evaluateNumericLiteral({ node }) {
    return Number(node.text);
}

/**
 * Evaluates, or attempts to evaluate, a BooleanLiteral
 */
function evaluateBooleanLiteral({ node, typescript }) {
    return node.kind === typescript.SyntaxKind.TrueKeyword;
}

/**
 * Evaluates, or attempts to evaluate, a RegularExpressionLiteral
 */
function evaluateRegularExpressionLiteral({ node, environment }) {
    const functionCtor = getFromLexicalEnvironment(node, environment, "Function").literal;
    return new functionCtor(`return ${node.text}`)();
}

/**
 * Evaluates, or attempts to evaluate, a ObjectLiteralExpression
 */
function evaluateObjectLiteralExpression(options) {
    const { node, evaluate, environment, getCurrentError } = options;
    // Create a new ObjectLiteral based on the Object implementation from the Realm since this must not be the same as in the parent executing context
    // Otherwise, instanceof checks would fail
    const objectCtor = getFromLexicalEnvironment(node, environment, "Object").literal;
    const value = objectCtor.create(objectCtor.prototype);
    // Mark the object as the 'this' value of the scope
    setInLexicalEnvironment({ ...options, path: THIS_SYMBOL, value, newBinding: true });
    for (const property of node.properties) {
        evaluate.nodeWithArgument(property, value, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    return value;
}

/**
 * Returns true if the given item is an Iterable
 *
 * @param item
 * @return
 */
function isIterable(item) {
    return item != null && item[Symbol.iterator] != null;
}

/**
 * Evaluates, or attempts to evaluate, a ArrayLiteralExpression
 */
function evaluateArrayLiteralExpression(options) {
    const { node, environment, evaluate, typescript, getCurrentError } = options;
    // Get the Array constructor from the realm - not that of the executing context. Otherwise, instanceof checks would fail
    const arrayCtor = getFromLexicalEnvironment(node, environment, "Array").literal;
    const value = arrayCtor.of();
    for (const element of node.elements) {
        const nextValue = evaluate.expression(element, options);
        if (getCurrentError() != null) {
            return;
        }
        if (typescript.isSpreadElement(element) && isIterable(nextValue)) {
            value.push(...nextValue);
        }
        else {
            value.push(nextValue);
        }
    }
    return value;
}

/**
 * An Error that can be thrown when an undefined identifier is encountered
 */
class UndefinedIdentifierError extends EvaluationError {
    constructor({ node, environment, message = `'${node.text}' is not defined'` }) {
        super({ message, environment, node });
    }
}

/**
 * Evaluates, or attempts to evaluate, an Identifier or a PrivateIdentifier
 */
function evaluateIdentifier(options) {
    const { node, environment, typeChecker, evaluate, stack, logger, reporting, typescript, throwError, getCurrentError } = options;
    // Otherwise, try to resolve it. Maybe it exists in the environment already?
    const environmentMatch = getFromLexicalEnvironment(node, environment, node.text);
    if (environmentMatch != null) {
        logger.logBinding(node.text, environmentMatch.literal, "Lexical Environment match");
        // Return the existing evaluated value from the environment
        return environmentMatch.literal;
    }
    // Try to get a symbol for whatever the identifier points to and take its value declaration.
    // It may not have a symbol, for example if it is an inlined expression such as an initializer or a Block
    const symbol = typescript.isShorthandPropertyAssignment(node.parent) ? typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getShorthandAssignmentValueSymbol(node.parent) : typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getSymbolAtLocation(node);
    let valueDeclaration = symbol == null ? undefined : symbol.valueDeclaration;
    if (symbol != null && valueDeclaration == null) {
        try {
            // The symbol may be aliasing another one - which may have a value declaration
            const aliasedSymbol = typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getAliasedSymbol(symbol);
            valueDeclaration = aliasedSymbol === null || aliasedSymbol === void 0 ? void 0 : aliasedSymbol.valueDeclaration;
        }
        catch {
            // OK, it didn't alias anything
        }
    }
    // If we weren't able to resolve a SourceFile still, try parsing the SourceFile manually
    if (valueDeclaration == null) {
        const result = findNearestParentNodeWithName(node.parent, node.text, options);
        if (getCurrentError() != null) {
            return;
        }
        if (isTypescriptNode(result) && !typescript.isIdentifier(result)) {
            valueDeclaration = result;
        }
        else if (result != null) {
            // Bind the value placed on the top of the stack to the local environment
            setInLexicalEnvironment({ ...options, path: node.text, value: result });
            logger.logBinding(node.text, result, `Discovered declaration value`);
            return result;
        }
    }
    // If it has a value declaration, go forward with that one
    if (valueDeclaration != null) {
        if (valueDeclaration.getSourceFile().isDeclarationFile) {
            const implementation = getImplementationForDeclarationWithinDeclarationFile({ ...options, node: valueDeclaration });
            if (getCurrentError() != null) {
                return;
            }
            // Bind the value placed on the top of the stack to the local environment
            setInLexicalEnvironment({ environment, path: node.text, value: implementation, reporting, node: valueDeclaration });
            logger.logBinding(node.text, implementation, `Discovered declaration value${valueDeclaration.getSourceFile() === node.getSourceFile() ? "" : ` (imported into '${node.getSourceFile().fileName}' from '${valueDeclaration.getSourceFile().fileName}')`}`);
            return implementation;
        }
        // If the value is a variable declaration and is located *after* the current node within the SourceFile
        // It is potentially a SyntaxError unless it is hoisted (if the 'var' keyword is being used) in which case the variable is defined, but initialized to 'undefined'
        if (typescript.isVariableDeclaration(valueDeclaration) && valueDeclaration.getSourceFile().fileName === node.getSourceFile().fileName && valueDeclaration.pos > node.pos) {
            // The 'var' keyword declares a variable that is defined, but which rvalue is still undefined
            if (typescript.isVariableDeclarationList(valueDeclaration.parent) && isVarDeclaration(valueDeclaration.parent, typescript)) {
                const returnValue = undefined;
                setInLexicalEnvironment({ ...options, path: node.text, value: returnValue, newBinding: true, node: valueDeclaration });
                logger.logBinding(node.text, returnValue, "Hoisted var declaration");
                return returnValue;
            }
            // In all other cases, both the identifier and the rvalue is still undefined
            else {
                return throwError(new UndefinedIdentifierError({ node, environment }));
            }
        }
        evaluate.declaration(valueDeclaration, options);
        if (getCurrentError() != null) {
            return;
        }
        const stackValue = stack.pop();
        // Bind the value placed on the top of the stack to the local environment
        setInLexicalEnvironment({ ...options, path: node.text, value: stackValue, node: valueDeclaration });
        logger.logBinding(node.text, stackValue, `Discovered declaration value${valueDeclaration.getSourceFile() === node.getSourceFile() ? "" : ` (imported into '${node.getSourceFile().fileName}' from '${valueDeclaration.getSourceFile().fileName}')`}`);
        return stackValue;
    }
    // Otherwise throw. The identifier is unknown
    return throwError(new UndefinedIdentifierError({ node, environment }));
}

/**
 * Evaluates, or attempts to evaluate, a Block
 */
function evaluateBlock(options) {
    const { node, environment, typescript, evaluate, getCurrentError } = options;
    // Prepare a lexical environment for the Block context
    const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
    for (let i = 0; i < node.statements.length; i++) {
        const statement = node.statements[i];
        // Don't execute 'super()' within Constructor Blocks since this is handled in another level
        if (typescript.isConstructorDeclaration(node.parent) &&
            i === 0 &&
            typescript.isExpressionStatement(statement) &&
            typescript.isCallExpression(statement.expression) &&
            isSuperExpression(statement.expression.expression, typescript)) {
            continue;
        }
        evaluate.statement(statement, { ...options, environment: localLexicalEnvironment });
        if (getCurrentError() != null)
            break;
        // Check if a 'break', 'continue', or 'return' statement has been encountered, break the block
        if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, BREAK_SYMBOL, CONTINUE_SYMBOL, RETURN_SYMBOL)) {
            break;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a ReturnStatement
 */
function evaluateReturnStatement({ node, evaluate, stack, ...options }) {
    const { getCurrentError } = options;
    setInLexicalEnvironment({ ...options, environment: options.environment, path: RETURN_SYMBOL, value: true, node });
    // If it is a simple 'return', return undefined
    if (node.expression == null) {
        stack.push(undefined);
    }
    else {
        const result = evaluate.expression(node.expression, options);
        if (getCurrentError() != null) {
            return;
        }
        stack.push(result);
    }
}

/**
 * Evaluates, or attempts to evaluate, a VariableDeclarationList
 */
function evaluateVariableDeclarationList({ node, evaluate, ...options }) {
    for (const declaration of node.declarations) {
        evaluate.declaration(declaration, options);
        if (options.getCurrentError() != null) {
            return;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a VariableStatement
 */
function evaluateVariableStatement({ node, ...rest }) {
    evaluateVariableDeclarationList({ node: node.declarationList, ...rest });
}

/**
 * Evaluates, or attempts to evaluate, a PrefixUnaryExpression
 */
function evaluatePrefixUnaryExpression(options) {
    var _a, _b;
    const { node, environment, evaluate, reporting, typescript, throwError, getCurrentError } = options;
    const operandValue = evaluate.expression(node.operand, options);
    if (getCurrentError() != null) {
        return;
    }
    switch (node.operator) {
        case typescript.SyntaxKind.PlusToken: {
            return +operandValue;
        }
        case typescript.SyntaxKind.MinusToken: {
            return -operandValue;
        }
        case typescript.SyntaxKind.TildeToken: {
            return ~operandValue;
        }
        case typescript.SyntaxKind.ExclamationToken: {
            // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
            return !operandValue;
        }
        case typescript.SyntaxKind.PlusPlusToken: {
            // If the Operand isn't an identifier, this will be a SyntaxError
            if (!typescript.isIdentifier(node.operand) && !((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node.operand))) {
                return throwError(new UnexpectedNodeError({ node: node.operand, environment, typescript }));
            }
            // Find the value associated with the identifier within the environment.
            const dict = getRelevantDictFromLexicalEnvironment(environment, node.operand.text);
            const value = ++dict[node.operand.text];
            // Inform reporting hooks if any is given
            if (reporting.reportBindings != null) {
                reporting.reportBindings({ path: node.operand.text, value, node });
            }
            return value;
        }
        case typescript.SyntaxKind.MinusMinusToken: {
            // If the Operand isn't an identifier, this will be a SyntaxError
            if (!typescript.isIdentifier(node.operand) && !((_b = typescript.isPrivateIdentifier) === null || _b === void 0 ? void 0 : _b.call(typescript, node.operand))) {
                return throwError(new UnexpectedNodeError({ node: node.operand, environment, typescript }));
            }
            // Find the value associated with the identifier within the environment.
            const dict = getRelevantDictFromLexicalEnvironment(environment, node.operand.text);
            const value = --dict[node.operand.text];
            // Inform reporting hooks if any is given
            if (reporting.reportBindings != null) {
                reporting.reportBindings({ path: node.operand.text, value, node });
            }
            return value;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a PropertyAccessExpression
 */
function evaluatePropertyAccessExpression(options) {
    const { evaluate, node, statementTraversalStack, environment, typescript, getCurrentError } = options;
    const expressionResult = evaluate.expression(node.expression, options);
    if (expressionResult == null || getCurrentError() != null) {
        return;
    }
    const match = node.questionDotToken != null && expressionResult == null
        ? // If optional chaining are being used and the expressionResult is undefined or null, assign undefined to 'match'
            undefined
        : expressionResult[node.name.text];
    // If it is a function, wrap it in a lazy call to preserve implicit 'this' bindings. This is to avoid losing the 'this' binding or having to
    // explicitly bind a 'this' value
    if (typeof match === "function" && statementTraversalStack.includes(typescript.SyntaxKind.CallExpression)) {
        return {
            [LAZY_CALL_FLAG]: LiteralFlagKind.CALL,
            invoke: (overriddenThis, ...args) => maybeThrow(node, options, overriddenThis != null && !isBindCallApply(match, environment)
                ? // eslint-disable-next-line @typescript-eslint/ban-types
                    expressionResult[node.name.text].call(overriddenThis, ...args)
                : expressionResult[node.name.text](...args))
        };
    }
    else
        return match;
}

/**
 * Evaluates, or attempts to evaluate, a ElementAccessExpression
 */
function evaluateElementAccessExpression(options) {
    const { node, environment, evaluate, statementTraversalStack, typescript, getCurrentError } = options;
    const expressionResult = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    const argumentExpressionResult = evaluate.expression(node.argumentExpression, options);
    if (getCurrentError() != null) {
        return;
    }
    const match = node.questionDotToken != null && expressionResult == null
        ? // If optional chaining are being used and the expressionResult is undefined or null, assign undefined to 'match'
            undefined
        : expressionResult[argumentExpressionResult];
    // If it is a function, wrap it in a lazy call to preserve implicit this bindings. This is to avoid losing the this binding or having to
    // explicitly bind a 'this' value
    if (typeof match === "function" && statementTraversalStack.includes(typescript.SyntaxKind.CallExpression)) {
        return {
            [LAZY_CALL_FLAG]: LiteralFlagKind.CALL,
            invoke: (overriddenThis, ...args) => maybeThrow(node, options, overriddenThis != null && !isBindCallApply(match, environment)
                ? // eslint-disable-next-line @typescript-eslint/ban-types
                    expressionResult[argumentExpressionResult].call(overriddenThis, ...args)
                : expressionResult[argumentExpressionResult](...args))
        };
    }
    else
        return match;
}

/**
 * Evaluates, or attempts to evaluate, a ComputedPropertyName
 */
function evaluateComputedPropertyName({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Evaluates, or attempts to evaluate, a FunctionDeclaration
 */
function evaluateFunctionDeclaration(options) {
    const { node, environment, evaluate, stack, typescript, getCurrentError } = options;
    const nameResult = node.name == null ? undefined : node.name.text;
    const _functionDeclaration = hasModifier(node, typescript.SyntaxKind.AsyncKeyword)
        ? async function functionDeclaration(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...options,
                node: node.parameters,
                environment: localLexicalEnvironment
            }, args);
            if (getCurrentError() != null) {
                return;
            }
            const sourceFile = node.getSourceFile();
            if (nameResult != null && sourceFile.isDeclarationFile) {
                const implementation = getImplementationForDeclarationWithinDeclarationFile(options);
                return implementation(...args);
            }
            // If the body is a block, evaluate it as a statement
            if (node.body == null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else {
                return undefined;
            }
        }
        : function functionDeclaration(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...nextOptions,
                node: node.parameters
            }, args);
            if (getCurrentError() != null) {
                return;
            }
            const sourceFile = node.getSourceFile();
            if (nameResult != null && sourceFile.isDeclarationFile) {
                const implementation = getImplementationForDeclarationWithinDeclarationFile(options);
                return implementation(...args);
            }
            // If the body is a block, evaluate it as a statement
            if (node.body == null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else
                return undefined;
        };
    if (nameResult != null) {
        setInLexicalEnvironment({ ...options, path: nameResult, value: _functionDeclaration.bind(_functionDeclaration) });
    }
    _functionDeclaration.toString = () => `[Function${nameResult == null ? "" : `: ${nameResult}`}]`;
    // Make sure to use the Function that is contained within the Realm. Otherwise, 'instanceof' checks may fail
    // since this particular function comes from the executing context.
    Object.setPrototypeOf(_functionDeclaration, getFromLexicalEnvironment(node, environment, "Function").literal);
    stack.push(_functionDeclaration);
}

/**
 * Evaluates, or attempts to evaluate, an IfStatement
 */
function evaluateIfStatement({ node, evaluate, ...options }) {
    const { getCurrentError } = options;
    const expressionValue = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    // We have to perform a loose boolean expression here to conform with actual spec behavior
    // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
    if (expressionValue) {
        // Proceed with the truthy branch
        evaluate.statement(node.thenStatement, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    // Proceed with the falsy branch
    else if (node.elseStatement != null) {
        return evaluate.statement(node.elseStatement, options);
    }
}

/**
 * Evaluates, or attempts to evaluate, an ExpressionStatement
 */
function evaluateExpressionStatement({ node, evaluate, stack, ...options }) {
    const result = evaluate.expression(node.expression, options);
    if (options.getCurrentError() != null) {
        return;
    }
    stack.push(result);
}

/**
 * Evaluates, or attempts to evaluate, a TemplateExpression
 */
function evaluateTemplateExpression({ node, evaluate, ...options }) {
    let str = "";
    str += node.head.text;
    for (const span of node.templateSpans) {
        const expression = evaluate.expression(span.expression, options);
        if (options.getCurrentError() != null) {
            return;
        }
        str += expression;
        str += span.literal.text;
    }
    return str;
}

/**
 * Evaluates, or attempts to evaluate, a TypeAssertion
 */
function evaluateTypeAssertion({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Evaluates, or attempts to evaluate, a PostfixUnaryExpression
 */
function evaluatePostfixUnaryExpression(options) {
    var _a, _b;
    const { evaluate, node, environment, typescript, throwError, reporting } = options;
    // Make sure to evaluate the operand to ensure that it is found in the lexical environment
    evaluate.expression(node.operand, options);
    switch (node.operator) {
        case typescript.SyntaxKind.PlusPlusToken: {
            // If the Operand isn't an identifier, this will be a SyntaxError
            if (!typescript.isIdentifier(node.operand) && !((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node.operand))) {
                return throwError(new UnexpectedNodeError({ node: node.operand, environment, typescript }));
            }
            // Find the value associated with the identifier within the environment.
            const value = getRelevantDictFromLexicalEnvironment(environment, node.operand.text)[node.operand.text]++;
            // Inform reporting hooks if any is given
            if (reporting.reportBindings != null) {
                reporting.reportBindings({ path: node.operand.text, value, node });
            }
            return value;
        }
        case typescript.SyntaxKind.MinusMinusToken: {
            // If the Operand isn't an identifier, this will be a SyntaxError
            if (!typescript.isIdentifier(node.operand) && !((_b = typescript.isPrivateIdentifier) === null || _b === void 0 ? void 0 : _b.call(typescript, node.operand))) {
                return throwError(new UnexpectedNodeError({ node: node.operand, environment, typescript }));
            }
            // Find the value associated with the identifier within the environment.
            const value = getRelevantDictFromLexicalEnvironment(environment, node.operand.text)[node.operand.text]--;
            // Inform reporting hooks if any is given
            if (reporting.reportBindings != null) {
                reporting.reportBindings({ path: node.operand.text, value, node });
            }
            return value;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a NewExpression
 */
function evaluateNewExpression({ node, evaluate, ...options }) {
    const { getCurrentError } = options;
    const evaluatedArgs = [];
    if (node.arguments != null) {
        for (let i = 0; i < node.arguments.length; i++) {
            evaluatedArgs[i] = evaluate.expression(node.arguments[i], options);
            if (getCurrentError() != null) {
                return;
            }
        }
    }
    // Evaluate the expression
    const expressionResult = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    // If the expression evaluated to a function, mark it as the [[NewTarget]], as per https://tc39.es/ecma262/multipage/executable-code-and-execution-contexts.html#sec-getnewtarget
    if (typeof expressionResult === "function") {
        setInLexicalEnvironment({ ...options, node, path: "[[NewTarget]]", value: expressionResult, newBinding: true });
    }
    return maybeThrow(node, options, new expressionResult(...evaluatedArgs));
}

/**
 * Evaluates, or attempts to evaluate, a NonNullExpression
 */
function evaluateNonNullExpression({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Evaluates, or attempts to evaluate, an AsExpression
 */
function evaluateAsExpression({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Evaluates, or attempts to evaluate, a SwitchStatement
 */
function evaluateSwitchStatement({ node, evaluate, ...options }) {
    const expressionResult = evaluate.expression(node.expression, options);
    if (options.getCurrentError() != null) {
        return;
    }
    evaluate.nodeWithArgument(node.caseBlock, expressionResult, options);
}

/**
 * An Error that can be thrown when an async iteration operation is attempted
 */
class AsyncIteratorNotSupportedError extends EvaluationError {
    constructor({ message = `It is not possible to evaluate an async iterator'`, typescript, environment }) {
        var _a, _b;
        super({
            message,
            environment,
            node: (_b = (_a = typescript.factory) === null || _a === void 0 ? void 0 : _a.createEmptyStatement()) !== null && _b !== void 0 ? _b : typescript.createEmptyStatement()
        });
    }
}

/**
 * Evaluates, or attempts to evaluate, a ForOfStatement
 */
function evaluateForOfStatement(options) {
    const { node, environment, evaluate, logger, typescript, throwError, getCurrentError } = options;
    // Compute the 'of' part
    const expressionResult = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    // Ensure that the initializer is a proper VariableDeclarationList
    if (!typescript.isVariableDeclarationList(node.initializer)) {
        return throwError(new UnexpectedNodeError({ node: node.initializer, environment, typescript }));
    }
    // Only 1 declaration is allowed in a ForOfStatement
    else if (node.initializer.declarations.length > 1) {
        return throwError(new UnexpectedNodeError({ node: node.initializer.declarations[1], environment, typescript }));
    }
    // As long as we only offer a synchronous API, there's no way to evaluate an async iterator in a synchronous fashion
    if (node.awaitModifier != null) {
        return throwError(new AsyncIteratorNotSupportedError({ typescript, environment }));
    }
    else {
        for (const literal of expressionResult) {
            // Prepare a lexical environment for the current iteration
            const localEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localEnvironment };
            // Define a new binding for a break symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: BREAK_SYMBOL, value: false, newBinding: true });
            // Define a new binding for a continue symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: CONTINUE_SYMBOL, value: false, newBinding: true });
            // Evaluate the VariableDeclaration and manually pass in the current literal as the initializer for the variable assignment
            evaluate.nodeWithArgument(node.initializer.declarations[0], literal, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // Evaluate the Statement
            evaluate.statement(node.statement, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // Check if a 'break' statement has been encountered and break if so
            if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, BREAK_SYMBOL)) {
                logger.logBreak(node, typescript);
                break;
            }
            else if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, CONTINUE_SYMBOL)) {
                logger.logContinue(node, typescript);
                // noinspection UnnecessaryContinueJS
                continue;
            }
            else if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, RETURN_SYMBOL)) {
                logger.logReturn(node, typescript);
                return;
            }
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a ThisExpression
 */
function evaluateThisExpression({ node, environment }) {
    const match = getFromLexicalEnvironment(node, environment, THIS_SYMBOL);
    return match == null ? undefined : match.literal;
}

/**
 * Evaluates, or attempts to evaluate, a BreakStatement
 */
function evaluateBreakStatement(options) {
    setInLexicalEnvironment({ ...options, path: BREAK_SYMBOL, value: true });
}

/**
 * Evaluates, or attempts to evaluate, a ContinueStatement
 */
function evaluateContinueStatement(options) {
    setInLexicalEnvironment({ ...options, path: CONTINUE_SYMBOL, value: true });
}

/**
 * Evaluates, or attempts to evaluate, a ForStatement
 */
function evaluateForStatement({ environment, evaluate, typescript, ...options }) {
    const { node, getCurrentError } = options;
    // Prepare a lexical environment for the ForStatement
    const forEnvironment = cloneLexicalEnvironment(environment, node);
    const forOptions = { ...options, environment: forEnvironment };
    // Evaluate the initializer if it is given
    if (node.initializer !== undefined) {
        if (typescript.isVariableDeclarationList(node.initializer)) {
            for (const declaration of node.initializer.declarations) {
                evaluate.declaration(declaration, forOptions);
                if (getCurrentError() != null) {
                    return;
                }
            }
        }
        else {
            evaluate.expression(node.initializer, forOptions);
            if (getCurrentError() != null) {
                return;
            }
        }
    }
    while (true) {
        // Prepare a lexical environment for the current iteration
        const iterationEnvironment = cloneLexicalEnvironment(forEnvironment, node);
        const iterationOptions = { ...options, environment: iterationEnvironment };
        // Define a new binding for a break symbol within the environment
        setInLexicalEnvironment({ ...iterationOptions, path: BREAK_SYMBOL, value: false, newBinding: true });
        // Define a new binding for a continue symbol within the environment
        setInLexicalEnvironment({ ...iterationOptions, path: CONTINUE_SYMBOL, value: false, newBinding: true });
        // Evaluate the condition. It may be truthy always
        const conditionResult = node.condition == null ? true : evaluate.expression(node.condition, forOptions);
        // If the condition doesn't hold, return immediately
        if (!conditionResult || getCurrentError() != null)
            return;
        // Execute the Statement
        evaluate.statement(node.statement, iterationOptions);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break' statement has been encountered and break if so
        if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, BREAK_SYMBOL)) {
            break;
        }
        else if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, RETURN_SYMBOL)) {
            return;
        }
        // Run the incrementor
        if (node.incrementor != null) {
            evaluate.expression(node.incrementor, forOptions);
            if (getCurrentError() != null) {
                return;
            }
        }
        // Always run the incrementor before continuing
        else if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, CONTINUE_SYMBOL)) {
            // noinspection UnnecessaryContinueJS
            continue;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a WhileStatement
 */
function evaluateWhileStatement(options) {
    const { node, environment, evaluate, logger, typescript, getCurrentError } = options;
    let condition = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    while (condition) {
        // Prepare a lexical environment for the current iteration
        const iterationEnvironment = cloneLexicalEnvironment(environment, node);
        const iterationOptions = { ...options, environment: iterationEnvironment };
        // Define a new binding for a break symbol within the environment
        setInLexicalEnvironment({ ...iterationOptions, path: BREAK_SYMBOL, value: false, newBinding: true });
        // Define a new binding for a continue symbol within the environment
        setInLexicalEnvironment({ ...iterationOptions, path: CONTINUE_SYMBOL, value: false, newBinding: true });
        // Execute the Statement
        evaluate.statement(node.statement, iterationOptions);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break' statement has been encountered and break if so
        if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, BREAK_SYMBOL)) {
            logger.logBreak(node, typescript);
            break;
        }
        else if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, RETURN_SYMBOL)) {
            logger.logReturn(node, typescript);
            return;
        }
        condition = evaluate.expression(node.expression, options);
        if (getCurrentError() != null) {
            return;
        }
        // Always re-evaluate the condition before continuing
        if (pathInLexicalEnvironmentEquals(node, iterationEnvironment, true, CONTINUE_SYMBOL)) {
            logger.logContinue(node, typescript);
            // noinspection UnnecessaryContinueJS
            continue;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a ForInStatement
 */
function evaluateForInStatement(options) {
    const { node, environment, evaluate, logger, typescript, throwError, getCurrentError } = options;
    // Compute the 'of' part
    const expressionResult = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    // Ensure that the initializer is a proper VariableDeclarationList
    if (!typescript.isVariableDeclarationList(node.initializer)) {
        return throwError(new UnexpectedNodeError({ node: node.initializer, environment, typescript }));
    }
    // Only 1 declaration is allowed in a ForOfStatement
    else if (node.initializer.declarations.length > 1) {
        return throwError(new UnexpectedNodeError({ node: node.initializer.declarations[1], environment, typescript }));
    }
    for (const literal in expressionResult) {
        // Prepare a lexical environment for the current iteration
        const localEnvironment = cloneLexicalEnvironment(environment, node);
        const nextOptions = { ...options, environment: localEnvironment };
        // Define a new binding for a break symbol within the environment
        setInLexicalEnvironment({ ...nextOptions, path: BREAK_SYMBOL, value: false, newBinding: true });
        // Define a new binding for a continue symbol within the environment
        setInLexicalEnvironment({ ...nextOptions, path: CONTINUE_SYMBOL, value: false, newBinding: true });
        // Evaluate the VariableDeclaration and manually pass in the current literal as the initializer for the variable assignment
        evaluate.nodeWithArgument(node.initializer.declarations[0], literal, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
        // Evaluate the Statement
        evaluate.statement(node.statement, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break' statement has been encountered and break if so
        if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, BREAK_SYMBOL)) {
            logger.logBreak(node, typescript);
            break;
        }
        else if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, CONTINUE_SYMBOL)) {
            logger.logContinue(node, typescript);
            // noinspection UnnecessaryContinueJS
            continue;
        }
        else if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, RETURN_SYMBOL)) {
            logger.logReturn(node, typescript);
            return;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a FunctionExpression
 */
function evaluateFunctionExpression(options) {
    const { node, environment, evaluate, stack, typescript, getCurrentError } = options;
    const nameResult = node.name == null ? undefined : node.name.text;
    const _functionExpression = hasModifier(node, typescript.SyntaxKind.AsyncKeyword)
        ? async function functionExpression(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...nextOptions,
                node: node.parameters
            }, args);
            // If the body is a block, evaluate it as a statement
            if (node.body == null || getCurrentError() != null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else
                return undefined;
        }
        : function functionExpression(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...nextOptions,
                node: node.parameters
            }, args);
            // If the body is a block, evaluate it as a statement
            if (node.body == null || getCurrentError() != null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else
                return undefined;
        };
    if (nameResult != null) {
        setInLexicalEnvironment({ ...options, path: nameResult, value: _functionExpression.bind(_functionExpression) });
    }
    _functionExpression.toString = () => `[Function${nameResult == null ? "" : `: ${nameResult}`}]`;
    // Make sure to use the Function that is contained within the Realm. Otherwise, 'instanceof' checks may fail
    // since this particular function comes from the executing context.
    Object.setPrototypeOf(_functionExpression, getFromLexicalEnvironment(node, environment, "Function").literal);
    return _functionExpression;
}

/**
 * An Error that can be thrown when a TryStatement is encountered without neither a catch {...} nor a finally {...} block
 */
class MissingCatchOrFinallyAfterTryError extends EvaluationError {
    constructor({ node, environment, message = `Missing catch or finally after try` }) {
        super({ node, environment, message });
    }
}

/**
 * Evaluates, or attempts to evaluate, a TryStatement
 */
function evaluateTryStatement(options) {
    const { node, evaluate, environment, throwError } = options;
    let error;
    const executeTry = () => {
        try {
            return evaluate.statement(node.tryBlock, {
                ...options,
                throwError: ex => {
                    error = ex;
                    return ex;
                },
                getCurrentError: () => error
            });
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
        }
        catch (ex) {
            error = ex;
        }
    };
    const executeCatch = (ex) => {
        // The CatchClause will declare an environment of its own
        evaluate.nodeWithArgument(node.catchClause, ex, options);
    };
    const executeFinally = () => {
        let finallyError;
        // The Block will declare an environment of its own
        evaluate.statement(node.finallyBlock, {
            ...options,
            throwError: ex => {
                finallyError = ex;
                // Also set it on the upper context
                options.throwError(ex);
                return ex;
            },
            getCurrentError: () => finallyError
        });
    };
    // A TryStatement must have either a catch or a finally block
    if (node.catchClause == null && node.finallyBlock == null) {
        return throwError(new MissingCatchOrFinallyAfterTryError({ node, environment }));
    }
    // Follows the form: try {...} catch {...}
    else if (node.catchClause != null && node.finallyBlock == null) {
        executeTry();
        if (error != null) {
            executeCatch(error);
        }
    }
    // Follows the form: try {...} catch {...} finally {...}
    else if (node.catchClause != null && node.finallyBlock != null) {
        executeTry();
        if (error != null) {
            executeCatch(error);
        }
        executeFinally();
    }
    // Follows the form: try {...} finally {...}
    else if (node.catchClause == null && node.finallyBlock != null) {
        executeTry();
        if (error != null) {
            throwError(error);
        }
        executeFinally();
    }
}

/**
 * A function that uses 'new Function' to auto-generate a class with a dynamic name and extended type
 */
function generateClassDeclaration({ name, extendedType, ctor = () => {
    // Noop
} }) {
    if (extendedType == null) {
        return new Function("ctor", `return class ${name == null ? "" : name} {constructor () {const ctorReturnValue = ctor.call(this, ...arguments); if (ctorReturnValue != null) return ctorReturnValue;}}`)(ctor);
    }
    else {
        return new Function("extendedType", "ctor", `return class ${name == null ? "" : name} extends extendedType {constructor () {super(...arguments); const ctorReturnValue = ctor.call(this, ...arguments); if (ctorReturnValue != null) return ctorReturnValue;}}`)(extendedType, ctor);
    }
}

function canHaveDecorators(node, typescript) {
    if ("canHaveDecorators" in typescript) {
        return typescript.canHaveDecorators(node);
    }
    else {
        return true;
    }
}
function getDecorators(node, typescript) {
    var _a;
    if ("getDecorators" in typescript) {
        return typescript.getDecorators(node);
    }
    else {
        const legacyDecorators = "decorators" in node && Array.isArray(node.decorators) ? node.decorators : undefined;
        const decoratorModifierLikes = (_a = node.modifiers) === null || _a === void 0 ? void 0 : _a.filter(modifier => "expression" in modifier);
        return [...(legacyDecorators !== null && legacyDecorators !== void 0 ? legacyDecorators : []), ...(decoratorModifierLikes !== null && decoratorModifierLikes !== void 0 ? decoratorModifierLikes : [])];
    }
}

/**
 * Evaluates, or attempts to evaluate, a ClassDeclaration
 */
function evaluateClassDeclaration(options) {
    var _a;
    const { node, evaluate, stack, logger, typescript, getCurrentError } = options;
    let extendedType;
    const ctorMember = node.members.find(typescript.isConstructorDeclaration);
    const otherMembers = node.members.filter(member => !typescript.isConstructorDeclaration(member));
    let ctor;
    if (ctorMember != null) {
        evaluate.declaration(ctorMember, options);
        if (getCurrentError() != null) {
            return;
        }
        ctor = stack.pop();
    }
    if (node.heritageClauses != null) {
        const extendsClause = node.heritageClauses.find(clause => clause.token === typescript.SyntaxKind.ExtendsKeyword);
        if (extendsClause != null) {
            const [firstExtendedType] = extendsClause.types;
            if (firstExtendedType != null) {
                extendedType = evaluate.expression(firstExtendedType.expression, options);
                if (getCurrentError() != null) {
                    return;
                }
            }
        }
    }
    const name = node.name == null ? undefined : node.name.text;
    let classDeclaration = generateClassDeclaration({ name, extendedType, ctor });
    if (canHaveDecorators(node, typescript)) {
        for (const decorator of (_a = getDecorators(node, typescript)) !== null && _a !== void 0 ? _a : []) {
            evaluate.nodeWithArgument(decorator, [classDeclaration], options);
            if (getCurrentError() != null) {
                return;
            }
            classDeclaration = stack.pop();
        }
    }
    classDeclaration.toString = () => `[Class${name == null ? "" : `: ${name}`}]`;
    if (name != null) {
        setInLexicalEnvironment({ ...options, path: name, value: classDeclaration, newBinding: true });
    }
    // Walk through all of the class members
    for (const member of otherMembers) {
        evaluate.nodeWithArgument(member, hasModifier(member, typescript.SyntaxKind.StaticKeyword) ? classDeclaration : classDeclaration.prototype, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    logger.logHeritage(classDeclaration);
    stack.push(classDeclaration);
    logger.logStack(stack);
}

/**
 * Evaluates, or attempts to evaluate, a ConstructorDeclaration
 */
function evaluateConstructorDeclaration(options) {
    const { node, environment, evaluate, stack, getCurrentError } = options;
    /**
     * An implementation of a constructor function
     */
    function constructor(...args) {
        // Don't concern yourself with calling super here as this constructor is called immediately after calling super() in another memory representation of a class
        // Prepare a lexical environment for the function context
        const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
        const nextOptions = { ...options, environment: localLexicalEnvironment };
        // Define a new binding for a return symbol within the environment
        setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
        // Define a new binding for the arguments given to the function
        // eslint-disable-next-line prefer-rest-params
        setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
        if (this != null) {
            setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
        }
        // Evaluate the parameters based on the given arguments
        evaluateParameterDeclarations({
            ...nextOptions,
            node: node.parameters
        }, args, this);
        // If the body is a block, evaluate it as a statement
        if (node.body == null || getCurrentError() != null)
            return;
        evaluate.statement(node.body, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
        // If a 'return' has occurred within the block, pop the Stack and return that value
        if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
            return stack.pop();
        }
        // Otherwise, return 'undefined'. Nothing is returned from the function
        else
            return undefined;
    }
    constructor.toString = () => "[Function: constructor]";
    stack.push(constructor);
}

/**
 * Evaluates, or attempts to evaluate, a SuperExpression
 */
function evaluateSuperExpression({ node, environment }) {
    const match = getFromLexicalEnvironment(node, environment, SUPER_SYMBOL);
    return match == null ? undefined : match.literal;
}

/**
 * Evaluates, or attempts to evaluate, a SpreadElement, before applying it on the given parent
 */
function evaluateSpreadElement({ node, evaluate, ...options }) {
    return evaluate.expression(node.expression, options);
}

/**
 * Evaluates, or attempts to evaluate, a ClassExpression
 */
function evaluateClassExpression(options) {
    var _a;
    const { node, evaluate, stack, logger, typescript, getCurrentError } = options;
    let extendedType;
    const ctorMember = node.members.find(typescript.isConstructorDeclaration);
    const otherMembers = node.members.filter(member => !typescript.isConstructorDeclaration(member));
    let ctor;
    if (ctorMember != null) {
        evaluate.declaration(ctorMember, options);
        if (getCurrentError() != null) {
            return;
        }
        ctor = stack.pop();
    }
    if (node.heritageClauses != null) {
        const extendsClause = node.heritageClauses.find(clause => clause.token === typescript.SyntaxKind.ExtendsKeyword);
        if (extendsClause != null) {
            const [firstExtendedType] = extendsClause.types;
            if (firstExtendedType != null) {
                extendedType = evaluate.expression(firstExtendedType.expression, options);
                if (getCurrentError() != null) {
                    return;
                }
            }
        }
    }
    const name = node.name == null ? undefined : node.name.text;
    let classExpression = generateClassDeclaration({ name, extendedType, ctor });
    if (canHaveDecorators(node, typescript)) {
        for (const decorator of (_a = getDecorators(node, typescript)) !== null && _a !== void 0 ? _a : []) {
            evaluate.nodeWithArgument(decorator, [classExpression], options);
            if (getCurrentError() != null) {
                return;
            }
            classExpression = stack.pop();
        }
    }
    classExpression.toString = () => `[Class${name == null ? "" : `: ${name}`}]`;
    if (name != null) {
        setInLexicalEnvironment({ ...options, path: name, value: classExpression, newBinding: true });
    }
    // Walk through all of the class members
    for (const member of otherMembers) {
        evaluate.nodeWithArgument(member, hasModifier(member, typescript.SyntaxKind.StaticKeyword) ? classExpression : classExpression.prototype, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    logger.logHeritage(classExpression);
    return classExpression;
}

/**
 * Evaluates, or attempts to evaluate, a NullLiteral
 */
// eslint-disable-next-line @typescript-eslint/no-unused-vars
function evaluateNullLiteral(_options) {
    return null;
}

/**
 * Evaluates, or attempts to evaluate, a VoidExpression
 */
function evaluateVoidExpression({ node, evaluate, ...options }) {
    evaluate.expression(node.expression, options);
    // The void operator evaluates the expression and then returns undefined
    return undefined;
}

/**
 * Evaluates, or attempts to evaluate, a TypeOfExpression
 */
function evaluateTypeOfExpression({ evaluate, node, ...options }) {
    const result = evaluate.expression(node.expression, options);
    if (options.getCurrentError() != null) {
        return;
    }
    return typeof result;
}

/**
 * Evaluates, or attempts to evaluate, a BigIntLiteral
 */
function evaluateBigIntLiteral({ node, environment }) {
    // Use BigInt from the Realm instead of the executing context such that instanceof checks won't fail, etc.
    const _BigInt = getFromLexicalEnvironment(node, environment, "BigInt").literal;
    // BigInt allows taking in strings, but they must appear as BigInt literals (e.g. "2n" is not allowed, but "2" is)
    return _BigInt(node.text.endsWith("n") ? node.text.slice(0, -1) : node.text);
}

/**
 * Evaluates, or attempts to evaluate, an EnumDeclaration
 */
function evaluateEnumDeclaration(options) {
    const { node, environment, evaluate, stack, getCurrentError } = options;
    // Create a new ObjectLiteral based on the Object implementation from the Realm since this must not be the same as in the parent executing context
    // Otherwise, instanceof checks would fail
    const objectCtor = getFromLexicalEnvironment(node, environment, "Object").literal;
    const enumDeclaration = objectCtor.create(objectCtor.prototype);
    const name = node.name.text;
    // Bind the Enum to the lexical environment as a new binding
    setInLexicalEnvironment({ ...options, path: name, value: enumDeclaration, newBinding: true });
    for (const member of node.members) {
        evaluate.nodeWithArgument(member, enumDeclaration, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    enumDeclaration.toString = () => `[Enum: ${name}]`;
    // Push the Enum declaration on to the Stack
    stack.push(enumDeclaration);
}

/**
 * Evaluates, or attempts to evaluate, a SourceFile as a namespace object
 */
function evaluateSourceFileAsNamespaceObject(options) {
    const { node, evaluate, environment, typeChecker, stack, getCurrentError } = options;
    // Create a new ObjectLiteral based on the Object implementation from the Realm since this must not be the same as in the parent executing context
    // Otherwise, instanceof checks would fail
    const objectCtor = getFromLexicalEnvironment(node, environment, "Object").literal;
    const namespaceObject = objectCtor.create(objectCtor.prototype);
    const moduleSymbol = typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getSymbolAtLocation(node);
    if (moduleSymbol != null) {
        const exports = moduleSymbol.exports;
        if (exports != null) {
            for (const [identifier, symbol] of exports.entries()) {
                const valueDeclaration = symbol.valueDeclaration;
                if (valueDeclaration == null)
                    return;
                evaluate.declaration(valueDeclaration, options);
                if (getCurrentError() != null) {
                    return;
                }
                namespaceObject[identifier] = stack.pop();
            }
        }
    }
    stack.push(namespaceObject);
}

/**
 * Evaluates, or attempts to evaluate, a ModuleDeclaration
 */
function evaluateModuleDeclaration(options) {
    const { getCurrentError, stack } = options;
    const result = getImplementationForDeclarationWithinDeclarationFile(options);
    if (getCurrentError() != null) {
        return;
    }
    stack.push(result);
}

/**
 * Evaluates, or attempts to evaluate, an ImportDeclaration (which is actually a Statement).
 */
function evaluateImportDeclaration({ node, evaluate, ...options }) {
    if (node.importClause == null)
        return;
    evaluate.declaration(node.importClause, options);
}

/**
 * Evaluates, or attempts to evaluate, a ThrowStatement
 */
function evaluateThrowStatement({ node, evaluate, ...options }) {
    const { getCurrentError, throwError } = options;
    const result = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    return throwError(result);
}

/**
 * Evaluates, or attempts to evaluate, an ImportEqualsDeclaration (which is actually a Statement).
 * It will be a noop, since we rely on the TypeChecker to resolve symbols across SourceFiles,
 * rather than manually parsing and resolving imports/exports
 */
// eslint-disable-next-line @typescript-eslint/no-unused-vars
function evaluateImportEqualsDeclaration(_options) {
    // Noop
}

/**
 * An Error that can be thrown when the maximum amount of operations dictated by the policy is exceeded
 */
class MaxOpDurationExceededError extends PolicyError {
    constructor({ duration, environment, node, message = `Maximum operation duration exceeded: ${duration}` }) {
        super({ violation: "maxOpDuration", message, node, environment });
        this.duration = duration;
    }
}

/**
 * Evaluates, or attempts to evaluate, an AwaitExpression
 */
async function evaluateAwaitExpression(options) {
    const { node, environment, evaluate, policy, throwError, getCurrentError } = options;
    // If a maximum duration for any operation is given, set a timeout that will throw a PolicyError when and if the duration is exceeded.
    const timeout = policy.maxOpDuration === Infinity
        ? undefined
        : setTimeout(() => {
            throwError(new MaxOpDurationExceededError({ duration: policy.maxOpDuration, node, environment }));
        }, policy.maxOpDuration);
    const result = evaluate.expression(node.expression, options);
    // Make sure to clear the timeout if it exists to avoid throwing unnecessarily
    if (timeout != null)
        clearTimeout(timeout);
    if (getCurrentError() != null) {
        return;
    }
    // Return the evaluated result
    return result;
}

/**
 * Evaluates, or attempts to evaluate, a ConditionalExpression
 */
function evaluateConditionalExpression({ node, evaluate, ...options }) {
    const { getCurrentError } = options;
    const conditionValue = evaluate.expression(node.condition, options);
    if (getCurrentError() != null) {
        return;
    }
    // We have to perform a loose boolean expression here to conform with actual spec behavior
    // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
    if (conditionValue) {
        // Proceed with the truthy branch
        return evaluate.expression(node.whenTrue, options);
    }
    // Proceed with the falsy branch
    return evaluate.expression(node.whenFalse, options);
}

/**
 * Returns true if the given Node exists within a static context
 */
function inStaticContext(node, typescript) {
    let currentNode = node;
    while (currentNode != null && !typescript.isSourceFile(currentNode)) {
        if (hasModifier(currentNode, typescript.SyntaxKind.StaticKeyword))
            return true;
        currentNode = currentNode.parent;
    }
    return false;
}

/**
 * Evaluates, or attempts to evaluate, a MethodDeclaration, before setting it on the given parent
 */
function evaluateMethodDeclaration(options, parent) {
    var _a, _b;
    const { node, environment, evaluate, stack, typescript, getCurrentError } = options;
    const nameResult = evaluate.nodeWithValue(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    const isStatic = inStaticContext(node, typescript);
    if (parent == null) {
        let updatedParent;
        if (typescript.isClassLike(node.parent)) {
            evaluate.declaration(node.parent, options);
            if (getCurrentError() != null) {
                return;
            }
            updatedParent = stack.pop();
        }
        else {
            updatedParent = evaluate.expression(node.parent, options);
            if (getCurrentError() != null) {
                return;
            }
        }
        stack.push(isStatic ? updatedParent[nameResult] : updatedParent.prototype[nameResult]);
        return;
    }
    const _methodDeclaration = hasModifier(node, typescript.SyntaxKind.AsyncKeyword)
        ? async function methodDeclaration(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
                // Set the 'super' binding, depending on whether or not we're inside a static context
                setInLexicalEnvironment({
                    ...nextOptions,
                    path: SUPER_SYMBOL,
                    value: isStatic ? Object.getPrototypeOf(this) : Object.getPrototypeOf(this.constructor).prototype,
                    newBinding: true
                });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...options,
                node: node.parameters,
                environment: localLexicalEnvironment
            }, args);
            // If the body is a block, evaluate it as a statement
            if (node.body == null || getCurrentError() != null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else
                return undefined;
        }
        : function methodDeclaration(...args) {
            // Prepare a lexical environment for the function context
            const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
            const nextOptions = { ...options, environment: localLexicalEnvironment };
            // Define a new binding for a return symbol within the environment
            setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
            // Define a new binding for the arguments given to the function
            // eslint-disable-next-line prefer-rest-params
            setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
            if (this != null) {
                setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
                // Set the 'super' binding, depending on whether or not we're inside a static context
                setInLexicalEnvironment({
                    ...nextOptions,
                    path: SUPER_SYMBOL,
                    value: isStatic ? Object.getPrototypeOf(this) : Object.getPrototypeOf(this.constructor).prototype,
                    newBinding: true
                });
            }
            // Evaluate the parameters based on the given arguments
            evaluateParameterDeclarations({
                ...options,
                node: node.parameters,
                environment: localLexicalEnvironment
            }, args);
            // If the body is a block, evaluate it as a statement
            if (node.body == null || getCurrentError() != null)
                return;
            evaluate.statement(node.body, nextOptions);
            if (getCurrentError() != null) {
                return;
            }
            // If a 'return' has occurred within the block, pop the Stack and return that value
            if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
                return stack.pop();
            }
            // Otherwise, return 'undefined'. Nothing is returned from the function
            else
                return undefined;
        };
    _methodDeclaration.toString = () => `[Method: ${nameResult}]`;
    // Make sure to use the Function that is contained within the Realm. Otherwise, 'instanceof' checks may fail
    // since this particular function comes from the executing context.
    Object.setPrototypeOf(_methodDeclaration, getFromLexicalEnvironment(node, environment, "Function").literal);
    parent[nameResult] = _methodDeclaration;
    if (canHaveDecorators(node, typescript)) {
        for (const decorator of (_a = getDecorators(node, typescript)) !== null && _a !== void 0 ? _a : []) {
            evaluate.nodeWithArgument(decorator, [parent, nameResult], options);
            if (getCurrentError() != null) {
                return;
            }
            // Pop the stack. We don't need the value it has left on the Stack
            stack.pop();
        }
    }
    // Also loop through parameters to use their decorators, if any
    if (node.parameters != null) {
        // 'this' is a special parameter which is removed from the emitted results
        const parameters = node.parameters.filter(param => !(typescript.isIdentifier(param.name) && param.name.text === "this"));
        for (let i = 0; i < parameters.length; i++) {
            const parameter = parameters[i];
            if (canHaveDecorators(parameter, typescript)) {
                for (const decorator of (_b = getDecorators(parameter, typescript)) !== null && _b !== void 0 ? _b : []) {
                    evaluate.nodeWithArgument(decorator, [parent, nameResult, i], options);
                    if (getCurrentError() != null) {
                        return;
                    }
                    // Pop the stack. We don't need the value it has left on the Stack
                    stack.pop();
                }
            }
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a PropertyDeclaration, before applying it on the given parent
 */
function evaluatePropertyDeclaration({ node, evaluate, typescript, stack, ...options }, parent) {
    var _a;
    const { getCurrentError } = options;
    // Compute the property name
    const propertyNameResult = evaluate.nodeWithValue(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    if (parent == null) {
        evaluate.declaration(node.parent, options);
        if (getCurrentError() != null) {
            return;
        }
        const updatedParent = stack.pop();
        const isStatic = inStaticContext(node, typescript);
        stack.push(isStatic ? updatedParent[propertyNameResult] : updatedParent.prototype[propertyNameResult]);
        return;
    }
    parent[propertyNameResult] = node.initializer == null ? undefined : evaluate.expression(node.initializer, options);
    if (getCurrentError() != null) {
        return;
    }
    if (canHaveDecorators(node, typescript)) {
        for (const decorator of (_a = getDecorators(node, typescript)) !== null && _a !== void 0 ? _a : []) {
            evaluate.nodeWithArgument(decorator, [parent, propertyNameResult], options);
            if (getCurrentError() != null) {
                return;
            }
            // Pop the stack. We don't need the value it has left on the Stack
            stack.pop();
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a GetAccessorDeclaration, before setting it on the given parent
 */
function evaluateGetAccessorDeclaration(options, parent) {
    const { node, environment, evaluate, stack, typescript, getCurrentError } = options;
    // We might be attempting to evaluate GetAccessorDeclaration that is placed within an ambient
    // context such as an InterfaceDeclaration, in which case there's nothing to evaluate
    if (typescript.isTypeLiteralNode(node.parent) || typescript.isInterfaceDeclaration(node.parent)) {
        return;
    }
    const nameResult = evaluate.nodeWithValue(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    const isStatic = inStaticContext(node, typescript);
    if (parent == null) {
        let updatedParent;
        if (typescript.isClassLike(node.parent)) {
            evaluate.declaration(node.parent, options);
            if (getCurrentError() != null) {
                return;
            }
            updatedParent = stack.pop();
        }
        else {
            updatedParent = evaluate.expression(node.parent, options);
            if (getCurrentError() != null) {
                return;
            }
        }
        stack.push(isStatic ? updatedParent[nameResult] : updatedParent.prototype[nameResult]);
        return;
    }
    /**
     * An implementation of the get accessor
     */
    function getAccessorDeclaration() {
        // Prepare a lexical environment for the function context
        const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
        const nextOptions = { ...options, environment: localLexicalEnvironment };
        // Define a new binding for a return symbol within the environment
        setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
        // Define a new binding for the arguments given to the function
        // eslint-disable-next-line prefer-rest-params
        setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
        if (this != null) {
            setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            // Set the 'super' binding, depending on whether or not we're inside a static context
            setInLexicalEnvironment({
                ...nextOptions,
                path: SUPER_SYMBOL,
                value: isStatic ? Object.getPrototypeOf(this) : Object.getPrototypeOf(this.constructor).prototype,
                newBinding: true
            });
        }
        // If the body is a block, evaluate it as a statement
        if (node.body == null)
            return;
        evaluate.statement(node.body, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
        // If a 'return' has occurred within the block, pop the Stack and return that value
        if (pathInLexicalEnvironmentEquals(node, localLexicalEnvironment, true, RETURN_SYMBOL)) {
            return stack.pop();
        }
        // Otherwise, return 'undefined'. Nothing is returned from the function
        else
            return undefined;
    }
    getAccessorDeclaration.toString = () => `[Get: ${nameResult}]`;
    let currentPropertyDescriptor = Object.getOwnPropertyDescriptor(parent, nameResult);
    if (currentPropertyDescriptor == null)
        currentPropertyDescriptor = {};
    Object.defineProperty(parent, nameResult, {
        ...currentPropertyDescriptor,
        configurable: true,
        get: getAccessorDeclaration
    });
}

/**
 * Evaluates, or attempts to evaluate, a TypeAliasDeclaration
 */
// eslint-disable-next-line @typescript-eslint/no-unused-vars
function evaluateTypeAliasDeclaration(_options) {
    return;
}

/**
 * Evaluates, or attempts to evaluate, a TypeAliasDeclaration
 */
// eslint-disable-next-line @typescript-eslint/no-unused-vars
function evaluateInterfaceDeclaration(_options) {
    return;
}

/**
 * Evaluates, or attempts to evaluate, an ImportClause.
 * It will only initialize the bindings inside the lexical environment, but not resolve them, since we rely on the TypeChecker to resolve symbols across SourceFiles,
 * rather than manually parsing and resolving imports/exports
 */
function evaluateImportClause({ node, evaluate, ...options }) {
    const { getCurrentError } = options;
    if (node.name != null) {
        evaluate.declaration(node.name, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    if (node.namedBindings != null) {
        if ("elements" in node.namedBindings) {
            for (const importSpecifier of node.namedBindings.elements) {
                evaluate.declaration(importSpecifier, options);
                if (getCurrentError() != null) {
                    return;
                }
            }
        }
        else {
            evaluate.declaration(node.namedBindings.name, options);
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, an ImportSpecifier.
 * It will only initialize the bindings inside the lexical environment, but not resolve them, since we rely on the TypeChecker to resolve symbols across SourceFiles,
 * rather than manually parsing and resolving imports/exports
 */
function evaluateImportSpecifier({ node, evaluate, ...options }) {
    var _a;
    evaluate.declaration((_a = node.propertyName) !== null && _a !== void 0 ? _a : node.name, options);
}

/**
 * Evaluates, or attempts to evaluate, a NamespaceImport.
 * It will only initialize the bindings inside the lexical environment, but not resolve them, since we rely on the TypeChecker to resolve symbols across SourceFiles,
 * rather than manually parsing and resolving imports/exports
 */
function evaluateNamespaceImport({ node, evaluate, ...options }) {
    evaluate.declaration(node.name, options);
}

/**
 * An Error that can be thrown when a certain usage is to be considered a SyntaxError
 */
class UnexpectedSyntaxError extends EvaluationError {
    constructor({ node, environment, message = `'SyntaxError'` }) {
        super({ message, environment, node });
    }
}

/**
 * Evaluates, or attempts to evaluate, a MetaProperty.
 */
function evaluateMetaProperty({ node, typescript, throwError, environment }) {
    var _a, _b;
    switch (node.keywordToken) {
        case typescript.SyntaxKind.NewKeyword: {
            switch (node.name.text) {
                case "target":
                    return (_a = getFromLexicalEnvironment(node, environment, "[[NewTarget]]")) === null || _a === void 0 ? void 0 : _a.literal;
                default:
                    return throwError(new UnexpectedSyntaxError({ node: node.name, environment }));
            }
        }
        case typescript.SyntaxKind.ImportKeyword: {
            switch (node.name.text) {
                case "meta":
                    return (_b = getFromLexicalEnvironment(node, environment, "import.meta")) === null || _b === void 0 ? void 0 : _b.literal;
                default:
                    return throwError(new UnexpectedSyntaxError({ node: node.name, environment }));
            }
        }
    }
}

/**
 * Will get a literal value for the given Node. If it doesn't succeed, the value will be 'undefined'
 */
function evaluateNode({ node, ...rest }) {
    var _a, _b, _c, _d, _e, _f;
    if (rest.typescript.isIdentifier(node)) {
        return evaluateIdentifier({ node, ...rest });
    }
    else if ((_b = (_a = rest.typescript).isPrivateIdentifier) === null || _b === void 0 ? void 0 : _b.call(_a, node)) {
        return evaluateIdentifier({ node, ...rest });
    }
    else if (rest.typescript.isStringLiteralLike(node)) {
        return evaluateStringLiteral({ node, ...rest });
    }
    else if (rest.typescript.isNumericLiteral(node)) {
        return evaluateNumericLiteral({ node, ...rest });
    }
    else if (isBooleanLiteral(node, rest.typescript)) {
        return evaluateBooleanLiteral({ node, ...rest });
    }
    else if (rest.typescript.isForOfStatement(node)) {
        return evaluateForOfStatement({ node, ...rest });
    }
    else if (rest.typescript.isForInStatement(node)) {
        return evaluateForInStatement({ node, ...rest });
    }
    else if (rest.typescript.isForStatement(node)) {
        return evaluateForStatement({ node, ...rest });
    }
    else if (rest.typescript.isWhileStatement(node)) {
        return evaluateWhileStatement({ node, ...rest });
    }
    else if (rest.typescript.isRegularExpressionLiteral(node)) {
        return evaluateRegularExpressionLiteral({ node, ...rest });
    }
    else if (rest.typescript.isObjectLiteralExpression(node)) {
        return evaluateObjectLiteralExpression({ node, ...rest });
    }
    else if (rest.typescript.isAwaitExpression(node)) {
        return evaluateAwaitExpression({ node, ...rest });
    }
    else if (((_d = (_c = rest.typescript).isTypeAssertionExpression) === null || _d === void 0 ? void 0 : _d.call(_c, node)) ||
        (!("isTypeAssertionExpression" in rest.typescript) && rest.typescript.isTypeAssertion(node))) {
        return evaluateTypeAssertion({ node, ...rest });
    }
    else if (rest.typescript.isTemplateExpression(node)) {
        return evaluateTemplateExpression({ node, ...rest });
    }
    else if (rest.typescript.isMethodDeclaration(node)) {
        return evaluateMethodDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isPropertyDeclaration(node)) {
        return evaluatePropertyDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isGetAccessorDeclaration(node)) {
        return evaluateGetAccessorDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isArrayLiteralExpression(node)) {
        return evaluateArrayLiteralExpression({ node, ...rest });
    }
    else if (rest.typescript.isSourceFile(node)) {
        return evaluateSourceFileAsNamespaceObject({ node, ...rest });
    }
    else if (rest.typescript.isModuleDeclaration(node)) {
        return evaluateModuleDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isPrefixUnaryExpression(node)) {
        return evaluatePrefixUnaryExpression({ node, ...rest });
    }
    else if (rest.typescript.isPostfixUnaryExpression(node)) {
        return evaluatePostfixUnaryExpression({ node, ...rest });
    }
    else if (rest.typescript.isVariableStatement(node)) {
        return evaluateVariableStatement({ node, ...rest });
    }
    else if (rest.typescript.isComputedPropertyName(node)) {
        return evaluateComputedPropertyName({ node, ...rest });
    }
    else if (rest.typescript.isVariableDeclarationList(node)) {
        return evaluateVariableDeclarationList({ node, ...rest });
    }
    else if (rest.typescript.isImportDeclaration(node)) {
        return evaluateImportDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isImportClause(node)) {
        return evaluateImportClause({ node, ...rest });
    }
    else if (rest.typescript.isImportSpecifier(node)) {
        return evaluateImportSpecifier({ node, ...rest });
    }
    else if (rest.typescript.isNamespaceImport(node)) {
        return evaluateNamespaceImport({ node, ...rest });
    }
    else if (rest.typescript.isImportEqualsDeclaration(node)) {
        return evaluateImportEqualsDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isMetaProperty(node)) {
        return evaluateMetaProperty({ node, ...rest });
    }
    else if (rest.typescript.isThrowStatement(node)) {
        return evaluateThrowStatement({ node, ...rest });
    }
    else if (rest.typescript.isVariableDeclaration(node)) {
        return evaluateVariableDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isEnumDeclaration(node)) {
        return evaluateEnumDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isConstructorDeclaration(node)) {
        return evaluateConstructorDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isBinaryExpression(node)) {
        return evaluateBinaryExpression({ node, ...rest });
    }
    else if (rest.typescript.isParenthesizedExpression(node)) {
        return evaluateParenthesizedExpression({ node, ...rest });
    }
    else if (rest.typescript.isExpressionStatement(node)) {
        return evaluateExpressionStatement({ node, ...rest });
    }
    else if (rest.typescript.isArrowFunction(node)) {
        return evaluateArrowFunctionExpression({ node, ...rest });
    }
    else if (rest.typescript.isFunctionDeclaration(node)) {
        return evaluateFunctionDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isFunctionExpression(node)) {
        return evaluateFunctionExpression({ node, ...rest });
    }
    else if (rest.typescript.isClassDeclaration(node)) {
        return evaluateClassDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isIfStatement(node)) {
        return evaluateIfStatement({ node, ...rest });
    }
    else if (rest.typescript.isConditionalExpression(node)) {
        return evaluateConditionalExpression({ node, ...rest });
    }
    else if (rest.typescript.isPropertyAccessExpression(node)) {
        return evaluatePropertyAccessExpression({ node, ...rest });
    }
    else if (rest.typescript.isElementAccessExpression(node)) {
        return evaluateElementAccessExpression({ node, ...rest });
    }
    else if (rest.typescript.isCallExpression(node)) {
        return evaluateCallExpression({ node, ...rest });
    }
    else if (rest.typescript.isSwitchStatement(node)) {
        return evaluateSwitchStatement({ node, ...rest });
    }
    else if (rest.typescript.isNewExpression(node)) {
        return evaluateNewExpression({ node, ...rest });
    }
    else if (rest.typescript.isNonNullExpression(node)) {
        return evaluateNonNullExpression({ node, ...rest });
    }
    else if (rest.typescript.isAsExpression(node)) {
        return evaluateAsExpression({ node, ...rest });
    }
    else if (rest.typescript.isBlock(node)) {
        return evaluateBlock({ node, ...rest });
    }
    else if (rest.typescript.isClassExpression(node)) {
        return evaluateClassExpression({ node, ...rest });
    }
    else if (rest.typescript.isSpreadElement(node)) {
        return evaluateSpreadElement({ node, ...rest });
    }
    else if (rest.typescript.isTryStatement(node)) {
        return evaluateTryStatement({ node, ...rest });
    }
    else if (rest.typescript.isReturnStatement(node)) {
        return evaluateReturnStatement({ node, ...rest });
    }
    else if (isThisExpression(node, rest.typescript)) {
        return evaluateThisExpression({ node, ...rest });
    }
    else if (rest.typescript.isVoidExpression(node)) {
        return evaluateVoidExpression({ node, ...rest });
    }
    else if (rest.typescript.isTypeOfExpression(node)) {
        return evaluateTypeOfExpression({ node, ...rest });
    }
    else if (isSuperExpression(node, rest.typescript)) {
        return evaluateSuperExpression({ node, ...rest });
    }
    else if (isNullLiteral(node, rest.typescript)) {
        return evaluateNullLiteral({ node, ...rest });
    }
    else if ((_f = (_e = rest.typescript).isBigIntLiteral) === null || _f === void 0 ? void 0 : _f.call(_e, node)) {
        return evaluateBigIntLiteral({ node, ...rest });
    }
    else if (rest.typescript.isBreakStatement(node)) {
        return evaluateBreakStatement({ node, ...rest });
    }
    else if (rest.typescript.isContinueStatement(node)) {
        return evaluateContinueStatement({ node, ...rest });
    }
    else if (rest.typescript.isTypeAliasDeclaration(node)) {
        return evaluateTypeAliasDeclaration({ node, ...rest });
    }
    else if (rest.typescript.isInterfaceDeclaration(node)) {
        return evaluateInterfaceDeclaration({ node, ...rest });
    }
    else if (rest.getCurrentError() != null) {
        return;
    }
    else {
        return rest.throwError(new UnexpectedNodeError({ node, environment: rest.environment, typescript: rest.typescript }));
    }
}

/**
 * Creates a StatementTraversalStack
 */
function createStatementTraversalStack() {
    return [];
}

/**
 * Will get a literal value for the given Statement. If it doesn't succeed, the value will be 'undefined'
 */
function evaluateStatement(options) {
    options.logger.logNode(options.node, options.typescript);
    // Create a new Statement traversal stack (since this is a new statement)
    options.statementTraversalStack = createStatementTraversalStack();
    evaluateNode(options);
}

/**
 * Will get a literal value for the given Expression. If it doesn't succeed, the value will be 'undefined'
 */
function evaluateExpression(options) {
    const { getCurrentError } = options;
    options.logger.logNode(options.node, options.typescript);
    const value = evaluateNode(options);
    if (getCurrentError() != null) {
        return;
    }
    // Report intermediate results
    if (options.reporting.reportIntermediateResults != null) {
        options.reporting.reportIntermediateResults({
            node: options.node,
            value
        });
    }
    return value;
}

/**
 * Will get a literal value for the given Declaration. If it doesn't succeed, the value will be 'undefined'
 */
function evaluateDeclaration(options) {
    options.logger.logNode(options.node, options.typescript);
    evaluateNode(options);
}

/**
 * Evaluates, or attempts to evaluate, a BindingName, based on an initializer
 */
function evaluateBindingName({ node, evaluate, typescript, logger, ...options }, rightHandValue) {
    var _a;
    // If the declaration binds a simple identifier, bind that text to the environment
    if (typescript.isIdentifier(node) || ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node))) {
        setInLexicalEnvironment({ ...options, node, path: node.text, value: rightHandValue, newBinding: true });
        logger.logBinding(node.text, rightHandValue, "evaluateBindingName");
    }
    else {
        evaluate.nodeWithArgument(node, rightHandValue, options);
    }
}

/**
 * Evaluates, or attempts to evaluate, a SetAccessorDeclaration, before setting it on the given parent
 */
function evaluateSetAccessorDeclaration(options, parent) {
    const { node, environment, evaluate, typescript, getCurrentError } = options;
    const nameResult = evaluate.nodeWithValue(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    const isStatic = inStaticContext(node, typescript);
    /**
     * An implementation of the set accessor
     */
    function setAccessorDeclaration(...args) {
        // Prepare a lexical environment for the function context
        const localLexicalEnvironment = cloneLexicalEnvironment(environment, node);
        const nextOptions = { ...options, environment: localLexicalEnvironment };
        // Define a new binding for a return symbol within the environment
        setInLexicalEnvironment({ ...nextOptions, path: RETURN_SYMBOL, value: false, newBinding: true });
        // Define a new binding for the arguments given to the function
        // eslint-disable-next-line prefer-rest-params
        setInLexicalEnvironment({ ...nextOptions, path: "arguments", value: arguments, newBinding: true });
        if (this != null) {
            setInLexicalEnvironment({ ...nextOptions, path: THIS_SYMBOL, value: this, newBinding: true });
            // Set the 'super' binding, depending on whether or not we're inside a static context
            setInLexicalEnvironment({
                ...nextOptions,
                path: SUPER_SYMBOL,
                value: isStatic ? Object.getPrototypeOf(this) : Object.getPrototypeOf(this.constructor).prototype,
                newBinding: true
            });
        }
        // Evaluate the parameters based on the given arguments
        evaluateParameterDeclarations({
            ...nextOptions,
            node: node.parameters
        }, args);
        // If the body is a block, evaluate it as a statement
        if (node.body == null || getCurrentError() != null)
            return;
        evaluate.statement(node.body, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
    }
    setAccessorDeclaration.toString = () => `[Set: ${nameResult}]`;
    let currentPropertyDescriptor = Object.getOwnPropertyDescriptor(parent, nameResult);
    if (currentPropertyDescriptor == null)
        currentPropertyDescriptor = {};
    Object.defineProperty(parent, nameResult, {
        ...currentPropertyDescriptor,
        configurable: true,
        set: setAccessorDeclaration
    });
}

/**
 * Evaluates, or attempts to evaluate, a PropertyAssignment, before applying it on the given parent
 */
function evaluatePropertyAssignment({ node, evaluate, ...options }, parent) {
    const initializer = evaluate.expression(node.initializer, options);
    if (options.getCurrentError() != null) {
        return;
    }
    // Compute the property name
    const propertyNameResult = evaluate.nodeWithValue(node.name, options);
    if (options.getCurrentError() != null) {
        return;
    }
    parent[propertyNameResult] = initializer;
}

/**
 * Evaluates, or attempts to evaluate, a ParameterDeclaration
 */
function evaluateParameterDeclaration({ node, evaluate, logger, ...options }, boundArgument) {
    // Use the bound argument if it is given unless it is nullable and the node itself has an initializer
    const boundValue = boundArgument != null || node.initializer === undefined ? boundArgument : evaluate.expression(node.initializer, options);
    if (options.getCurrentError() != null) {
        return;
    }
    logger.logBinding(node.name.getText(), boundValue, "evaluateParameterDeclaration");
    evaluate.nodeWithArgument(node.name, boundValue, options);
}

/**
 * Evaluates, or attempts to evaluate, a ShorthandPropertyAssignment, before applying it on the given parent
 */
function evaluateShorthandPropertyAssignment({ node, evaluate, ...options }, parent) {
    const { getCurrentError } = options;
    const identifier = node.name.text;
    const initializer = evaluate.expression(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    parent[identifier] = initializer;
}

/**
 * Evaluates, or attempts to evaluate, a SpreadAssignment, before applying it on the given parent
 */
function evaluateSpreadAssignment({ node, evaluate, ...options }, parent) {
    const entries = evaluate.expression(node.expression, options);
    if (options.getCurrentError() != null) {
        return;
    }
    Object.assign(parent, entries);
}

/**
 * Evaluates, or attempts to evaluate, an ArrayBindingPattern, based on an initializer
 */
function evaluateArrayBindingPattern({ node, evaluate, ...options }, rightHandValue) {
    const iterator = rightHandValue[Symbol.iterator]();
    let elementsCursor = 0;
    while (elementsCursor < node.elements.length) {
        const { done, value } = iterator.next();
        if (done === true)
            break;
        evaluate.nodeWithArgument(node.elements[elementsCursor++], value, options);
        if (options.getCurrentError() != null) {
            return;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a BindingName, based on an BindingElement
 */
function evaluateBindingElement(options, rightHandValue) {
    var _a, _b, _c, _d;
    const { node, evaluate, logger, typescript, getCurrentError } = options;
    // Compute the initializer value of the BindingElement, if it has any, that is
    const bindingElementInitializer = node.initializer == null ? undefined : evaluate.expression(node.initializer, options);
    if (getCurrentError() != null) {
        return;
    }
    // If the element is directly references a property, but then aliases, store that alias in the environment.
    if ((typescript.isIdentifier(node.name) || ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node.name))) && node.propertyName != null) {
        // An element that is aliased cannot have a name that is anything other than an Identifier
        const aliasName = node.name.text;
        // Compute the property name
        const propertyNameResult = evaluate.nodeWithValue(node.propertyName, options);
        if (getCurrentError() != null) {
            return;
        }
        // Extract the property value from the initializer. If it is an ArrayBindingPattern, the rightHandValue will be assigned as-is to the identifier
        const propertyValue = typescript.isArrayBindingPattern(node.parent) ? rightHandValue : rightHandValue[propertyNameResult];
        // Fall back to using the initializer of the BindingElement if the property value is null-like and if it has one
        const propertyValueWithInitializerFallback = propertyValue != null ? propertyValue : bindingElementInitializer;
        setInLexicalEnvironment({
            ...options,
            path: aliasName,
            value: propertyValueWithInitializerFallback,
            newBinding: true
        });
    }
    // If the name is a simple non-aliased identifier, it directly references, a property from the right-hand value
    else if ((typescript.isIdentifier(node.name) || ((_b = typescript.isPrivateIdentifier) === null || _b === void 0 ? void 0 : _b.call(typescript, node.name))) && node.propertyName == null) {
        // Compute the literal value of the name of the node
        const nameResult = node.name.text;
        // Extract the property value from the initializer. If it is an ArrayBindingPattern, the rightHandValue will be assigned as-is to the identifier
        const propertyValue = typescript.isArrayBindingPattern(node.parent) ? rightHandValue : rightHandValue[nameResult];
        // Fall back to using the initializer of the BindingElement if the property value is null-like and if it has one
        const propertyValueWithInitializerFallback = propertyValue != null ? propertyValue : bindingElementInitializer;
        logger.logBinding(node.name.text, propertyValueWithInitializerFallback);
        setInLexicalEnvironment({
            ...options,
            path: node.name.text,
            value: propertyValueWithInitializerFallback,
            newBinding: true
        });
    }
    // Otherwise, the name is itself a BindingPattern, and the property it is destructuring will always be defined
    else if (!typescript.isIdentifier(node.name) && !((_c = typescript.isPrivateIdentifier) === null || _c === void 0 ? void 0 : _c.call(typescript, node.name)) && node.propertyName != null) {
        // Compute the property name
        const propertyNameResult = evaluate.nodeWithValue(node.propertyName, options);
        if (getCurrentError() != null) {
            return;
        }
        // Extract the property value from the initializer. If it is an ArrayBindingPattern, the rightHandValue will be assigned as-is to the identifier
        const propertyValue = typescript.isArrayBindingPattern(node.parent) ? rightHandValue : rightHandValue[propertyNameResult];
        // Fall back to using the initializer of the BindingElement if the property value is null-like and if it has one
        const propertyValueWithInitializerFallback = propertyValue != null ? propertyValue : bindingElementInitializer;
        // Evaluate the BindingPattern based on the narrowed property value
        evaluate.nodeWithArgument(node.name, propertyValueWithInitializerFallback, options);
        if (getCurrentError() != null) {
            return;
        }
    }
    // Otherwise, the name itself is a BindingPattern. This will happen for example if an ObjectBindingPattern occurs within an ArrayBindingPattern
    else if (!typescript.isIdentifier(node.name) && !((_d = typescript.isPrivateIdentifier) === null || _d === void 0 ? void 0 : _d.call(typescript, node.name)) && node.propertyName == null) {
        // Fall back to using the initializer of the BindingElement if the property value is null-like and if it has one
        const propertyValueWithInitializerFallback = rightHandValue != null ? rightHandValue : bindingElementInitializer;
        evaluate.nodeWithArgument(node.name, propertyValueWithInitializerFallback, options);
        if (getCurrentError() != null) {
            return;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, an ObjectBindingPattern, based on an initializer
 */
function evaluateObjectBindingPattern({ node, evaluate, ...options }, rightHandValue) {
    for (const element of node.elements) {
        evaluate.nodeWithArgument(element, rightHandValue, options);
        if (options.getCurrentError() != null) {
            return;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a CaseBlock, based on a switch expression
 */
function evaluateCaseBlock(options, switchExpression) {
    const { node, evaluate, environment, getCurrentError } = options;
    // Prepare a lexical environment for the case block
    const localEnvironment = cloneLexicalEnvironment(environment, node);
    const nextOptions = { ...options, environment: localEnvironment };
    // Define a new binding for a break symbol within the environment
    setInLexicalEnvironment({ ...nextOptions, path: BREAK_SYMBOL, value: false, newBinding: true });
    for (const clause of node.clauses) {
        evaluate.nodeWithArgument(clause, switchExpression, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break', 'continue', or 'return' statement has been encountered, break the block
        if (pathInLexicalEnvironmentEquals(node, localEnvironment, true, BREAK_SYMBOL, CONTINUE_SYMBOL, RETURN_SYMBOL)) {
            break;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a CaseClause, based on a switch expression
 */
function evaluateCaseClause({ node, evaluate, ...options }, switchExpression) {
    const { getCurrentError } = options;
    const expressionResult = evaluate.expression(node.expression, options);
    // Stop immediately if the expression doesn't match the switch expression
    if (expressionResult !== switchExpression || getCurrentError() != null)
        return;
    for (const statement of node.statements) {
        evaluate.statement(statement, options);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break', 'continue', or 'return' statement has been encountered, break the block
        if (pathInLexicalEnvironmentEquals(node, options.environment, true, BREAK_SYMBOL, CONTINUE_SYMBOL, RETURN_SYMBOL)) {
            break;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a DefaultClause, based on a switch expression
 */
function evaluateDefaultClause(options) {
    const { node, evaluate, environment, getCurrentError } = options;
    for (const statement of node.statements) {
        evaluate.statement(statement, options);
        if (getCurrentError() != null) {
            return;
        }
        // Check if a 'break', 'continue', or 'return' statement has been encountered, break the block
        if (pathInLexicalEnvironmentEquals(node, environment, true, BREAK_SYMBOL, CONTINUE_SYMBOL, RETURN_SYMBOL)) {
            break;
        }
    }
}

/**
 * Evaluates, or attempts to evaluate, a CatchClause, based on a given Error
 */
function evaluateCatchClause(options, ex) {
    const { node, evaluate, environment, getCurrentError } = options;
    // If a catch binding is provided, we must provide a local lexical environment for the CatchBlock
    const catchEnvironment = node.variableDeclaration == null ? environment : cloneLexicalEnvironment(environment, node);
    const nextOptions = { ...options, environment: catchEnvironment };
    // Evaluate the catch binding, if any is provided
    if (node.variableDeclaration != null) {
        evaluate.nodeWithArgument(node.variableDeclaration, ex, nextOptions);
        if (getCurrentError() != null) {
            return;
        }
    }
    // Evaluate the block
    evaluate.statement(node.block, nextOptions);
}

/**
 * Evaluates, or attempts to evaluate, a OmittedExpression
 */
// eslint-disable-next-line @typescript-eslint/no-unused-vars
function evaluateOmittedExpression(_options) {
    return undefined;
}

/* eslint-disable @typescript-eslint/strict-boolean-expressions */
/**
 * This is ported over from tslib to avoid having it as a runtime dependency
 */
// eslint-disable-next-line @typescript-eslint/naming-convention
function __decorate(decorators, target, key, desc) {
    const c = arguments.length;
    let r = c < 3 ? target : desc === null ? (desc = Object.getOwnPropertyDescriptor(target, key)) : desc;
    let d;
    // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
    for (let i = decorators.length - 1; i >= 0; i--)
        if ((d = decorators[i]))
            r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    // eslint-disable-next-line no-sequences
    return c > 3 && r && Object.defineProperty(target, key, r), r;
}
/**
 * This is ported over from tslib to avoid having it as a runtime dependency
 */
// eslint-disable-next-line @typescript-eslint/naming-convention
function __param(paramIndex, decorator) {
    return function (target, key) {
        decorator(target, key, paramIndex);
    };
}

/**
 * Evaluates, or attempts to evaluate, a Decorator
 */
function evaluateDecorator(options, [parent, propertyName, index]) {
    const { node, evaluate, environment, throwError, stack, getCurrentError } = options;
    const decoratorImplementation = evaluate.expression(node.expression, options);
    if (getCurrentError() != null) {
        return;
    }
    if (typeof decoratorImplementation !== "function") {
        return throwError(new NotCallableError({
            node,
            environment,
            value: decoratorImplementation,
            message: `${stringifyLiteral(decoratorImplementation)} is not a valid decorator implementation'`
        }));
    }
    stack.push(__decorate([index != null ? __param(index, decoratorImplementation) : decoratorImplementation], parent, propertyName));
}

/**
 * Evaluates, or attempts to evaluate, an EnumMember
 */
function evaluateEnumMember(options, parent) {
    const { node, typeChecker, evaluate, getCurrentError } = options;
    let constantValue = typeChecker === null || typeChecker === void 0 ? void 0 : typeChecker.getConstantValue(node);
    // If the constant value is not defined, that must be due to the type checker either not being given or functioning incorrectly.
    // Calculate it manually instead
    if (constantValue == null) {
        if (node.initializer != null) {
            constantValue = evaluate.expression(node.initializer, options);
            if (getCurrentError() != null) {
                return;
            }
        }
        else {
            const siblings = node.parent.members;
            const thisIndex = siblings.findIndex(member => member === node);
            const beforeSiblings = siblings.slice(0, thisIndex);
            let traversal = 0;
            for (const sibling of [...beforeSiblings].reverse()) {
                traversal++;
                if (sibling.initializer != null) {
                    const siblingConstantValue = evaluate.expression(sibling.initializer, options);
                    if (getCurrentError() != null) {
                        return;
                    }
                    if (typeof siblingConstantValue === "number") {
                        constantValue = siblingConstantValue + traversal;
                        break;
                    }
                }
            }
            if (constantValue == null) {
                constantValue = thisIndex;
            }
        }
    }
    const propertyName = evaluate.nodeWithValue(node.name, options);
    if (getCurrentError() != null) {
        return;
    }
    // If it is a String enum, all keys will be initialized to strings
    if (typeof constantValue === "string") {
        parent[propertyName] = constantValue;
    }
    else {
        parent[(parent[propertyName] = constantValue !== null && constantValue !== void 0 ? constantValue : 0)] = propertyName;
    }
}

/**
 * Evaluates a given node with the provided argument
 */
function evaluateNodeWithArgument(options, arg) {
    options.logger.logNode(options.node, options.typescript, "nodeWithArgument");
    const { node, ...rest } = options;
    if (rest.typescript.isGetAccessorDeclaration(node)) {
        evaluateGetAccessorDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isSetAccessorDeclaration(node)) {
        evaluateSetAccessorDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isPropertyAssignment(node)) {
        evaluatePropertyAssignment({ node, ...rest }, arg);
    }
    else if (rest.typescript.isPropertyDeclaration(node)) {
        evaluatePropertyDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isParameter(node)) {
        evaluateParameterDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isEnumMember(node)) {
        evaluateEnumMember({ node, ...rest }, arg);
    }
    else if (rest.typescript.isShorthandPropertyAssignment(node)) {
        evaluateShorthandPropertyAssignment({ node, ...rest }, arg);
    }
    else if (rest.typescript.isDecorator(node)) {
        evaluateDecorator({ node, ...rest }, arg);
    }
    else if (rest.typescript.isSpreadAssignment(node)) {
        evaluateSpreadAssignment({ node, ...rest }, arg);
    }
    else if (rest.typescript.isMethodDeclaration(node)) {
        evaluateMethodDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isArrayBindingPattern(node)) {
        evaluateArrayBindingPattern({ node, ...rest }, arg);
    }
    else if (rest.typescript.isBindingElement(node)) {
        evaluateBindingElement({ node, ...rest }, arg);
    }
    else if (rest.typescript.isObjectBindingPattern(node)) {
        evaluateObjectBindingPattern({ node, ...rest }, arg);
    }
    else if (rest.typescript.isVariableDeclaration(node)) {
        evaluateVariableDeclaration({ node, ...rest }, arg);
    }
    else if (rest.typescript.isCaseBlock(node)) {
        evaluateCaseBlock({ node, ...rest }, arg);
    }
    else if (rest.typescript.isCaseClause(node)) {
        evaluateCaseClause({ node, ...rest }, arg);
    }
    else if (rest.typescript.isDefaultClause(node)) {
        evaluateDefaultClause({ node, ...rest });
    }
    else if (rest.typescript.isCatchClause(node)) {
        evaluateCatchClause({ node, ...rest }, arg);
    }
    else if (rest.typescript.isBindingName(node)) {
        evaluateBindingName({ node, ...rest }, arg);
    }
    else if (rest.typescript.isOmittedExpression(node)) {
        evaluateOmittedExpression({ node, ...rest });
    }
    else if (options.getCurrentError() != null) {
        return;
    }
    else {
        rest.throwError(new UnexpectedNodeError({ node, environment: rest.environment, typescript: rest.typescript }));
    }
}

/**
 * Evaluates, or attempts to evaluate, a PropertyName
 */
function evaluatePropertyName({ node, evaluate, typescript, ...options }) {
    var _a;
    return (typescript.isComputedPropertyName(node)
        ? evaluate.expression(node.expression, options)
        : typescript.isIdentifier(node) || ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node))
            ? node.text
            : evaluate.expression(node, options));
}

/**
 * Evaluates a given node with the provided argument
 */
function evaluateNodeWithValue(options) {
    options.logger.logNode(options.node, options.typescript, "nodeWithValue");
    const { node, ...rest } = options;
    // Until #37135 is resolved, isPropertyName will return false for PrivateIdentifiers (even though they are actually PropertyNames)
    if (options.typescript.isPropertyName(node) || options.typescript.isPrivateIdentifier(node)) {
        return evaluatePropertyName({ node, ...rest });
    }
    return options.throwError(new UnexpectedNodeError({ node, environment: options.environment, typescript: options.typescript }));
}

/**
 * Creates a Node Evaluator
 */
function createNodeEvaluator(options) {
    let ops = 0;
    const { policy, reporting } = options;
    const prequalifyNextNode = (node, nextOptions) => {
        const { environment = options.environment, statementTraversalStack = options.statementTraversalStack, getCurrentError = options.getCurrentError, throwError = options.throwError } = nextOptions !== null && nextOptions !== void 0 ? nextOptions : {};
        const currentError = getCurrentError();
        if (currentError != null) {
            return currentError;
        }
        // Increment the amount of encountered ops
        ops++;
        // Throw an error if the maximum amount of operations has been exceeded
        if (ops >= policy.maxOps) {
            return throwError(new MaxOpsExceededError({ ops, environment, node }));
        }
        // Update the statementTraversalStack with the node's kind
        statementTraversalStack.push(node.kind);
        if (reporting.reportTraversal != null) {
            reporting.reportTraversal({ node });
        }
        return undefined;
    };
    const evaluate = {
        statement: (node, nextOptions) => {
            const combinedNextOptions = { ...nextOptions, statementTraversalStack: createStatementTraversalStack() };
            const prequalifyResult = prequalifyNextNode(node, combinedNextOptions);
            if (isEvaluationError(prequalifyResult)) {
                return;
            }
            return evaluateStatement(getEvaluatorOptions(node, combinedNextOptions));
        },
        declaration: (node, nextOptions) => {
            const prequalifyResult = prequalifyNextNode(node, nextOptions);
            if (isEvaluationError(prequalifyResult)) {
                return;
            }
            return evaluateDeclaration(getEvaluatorOptions(node, nextOptions));
        },
        nodeWithArgument: (node, arg, nextOptions) => {
            const prequalifyResult = prequalifyNextNode(node, nextOptions);
            if (isEvaluationError(prequalifyResult)) {
                return;
            }
            return evaluateNodeWithArgument(getEvaluatorOptions(node, nextOptions), arg);
        },
        expression: (node, nextOptions) => {
            const prequalifyResult = prequalifyNextNode(node, nextOptions);
            if (isEvaluationError(prequalifyResult)) {
                return prequalifyResult;
            }
            return evaluateExpression(getEvaluatorOptions(node, nextOptions));
        },
        nodeWithValue: (node, nextOptions) => {
            const prequalifyResult = prequalifyNextNode(node, nextOptions);
            if (isEvaluationError(prequalifyResult)) {
                return prequalifyResult;
            }
            return evaluateNodeWithValue(getEvaluatorOptions(node, nextOptions));
        }
    };
    /**
     * Gets an IEvaluatorOptions object ready for passing to one of the evaluation functions
     */
    function getEvaluatorOptions(node, nextOptions) {
        return {
            ...options,
            ...nextOptions,
            evaluate,
            node
        };
    }
    return evaluate;
}

var LogLevelKind;
(function (LogLevelKind) {
    LogLevelKind[LogLevelKind["SILENT"] = 0] = "SILENT";
    LogLevelKind[LogLevelKind["INFO"] = 1] = "INFO";
    LogLevelKind[LogLevelKind["VERBOSE"] = 2] = "VERBOSE";
    LogLevelKind[LogLevelKind["DEBUG"] = 3] = "DEBUG";
})(LogLevelKind || (LogLevelKind = {}));

/**
 * Stringifies the given SyntaxKind
 */
function stringifySyntaxKind(kind, typescript) {
    if (kind === typescript.SyntaxKind.NumericLiteral)
        return "NumericLiteral";
    return typescript.SyntaxKind[kind];
}

/**
 * A simple logger for printing evaluation-related info
 */
class Logger {
    constructor(optionsOrLogLevel = {}) {
        const { logLevel = LogLevelKind.SILENT, color: { info = "white", verbose = "yellow", debug = "magenta" } = {} } = typeof optionsOrLogLevel === "object" ? optionsOrLogLevel : { logLevel: optionsOrLogLevel };
        this.options = {
            logLevel,
            color: {
                info,
                verbose,
                debug
            }
        };
    }
    /**
     * Logs info output if the log level allows it
     */
    logInfo(message) {
        if (this.options.logLevel < LogLevelKind.INFO)
            return;
        console.log(this.formatWithColor(this.options.color.info, message));
    }
    /**
     * Logs verbose output if the log level allows it
     */
    logVerbose(message) {
        if (this.options.logLevel < LogLevelKind.VERBOSE)
            return;
        console.log(this.formatWithColor(this.options.color.verbose, message));
    }
    /**
     * Logs debug output if the log level allows it
     */
    logDebug(message) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(this.formatWithColor(this.options.color.debug, message));
    }
    /**
     * Logs that a 'continue' keyword appeared within a statement
     */
    logContinue(node, typescript) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(`${this.formatWithColor(this.options.color.debug, `continue`)} encountered within ${this.formatWithColor(this.options.color.debug, stringifySyntaxKind(node.kind, typescript))}`);
    }
    /**
     * Logs that a 'break' keyword appeared within a statement
     */
    logBreak(node, typescript) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(`${this.formatWithColor(this.options.color.debug, `break`)} encountered within ${this.formatWithColor(this.options.color.debug, stringifySyntaxKind(node.kind, typescript))}`);
    }
    /**
     * Logs that a 'return' keyword appeared within a statement
     */
    logReturn(node, typescript) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(`${this.formatWithColor(this.options.color.debug, `return`)} encountered within ${this.formatWithColor(this.options.color.debug, stringifySyntaxKind(node.kind, typescript))}`);
    }
    /**
     * Logs the given result
     */
    logResult(result, intermediateContext) {
        if (this.options.logLevel < LogLevelKind.INFO)
            return;
        if (intermediateContext != null) {
            console.log(this.formatWithColor(this.options.color.info, `(intermediate value from context '${intermediateContext}'):`), this.formatWithColor(this.options.color.info, `[RESULT]:`), this.compactValue(result));
        }
        else {
            console.log(this.formatWithColor(this.options.color.info, `[RESULT]:`), result);
        }
    }
    /**
     * Logs the given evaluation
     */
    logNode(node, typescript, context) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        let headRaw = `[${stringifySyntaxKind(node.kind, typescript)}]`.padEnd(25);
        if (context != null)
            headRaw += this.formatWithColor(this.options.color.debug, `(${context})`);
        const tailRaw = node.getText();
        const head = this.formatWithColor(this.options.color.debug, headRaw);
        const tail = this.formatWithColor(this.options.color.debug, tailRaw);
        console.log(head);
        console.log(tail);
    }
    /**
     * Logs the given binding
     */
    logBinding(lValue, rValue, scope) {
        if (this.options.logLevel < LogLevelKind.VERBOSE)
            return;
        console.log(`${scope == null ? "" : this.formatWithColor(this.options.color.verbose, `(${scope}): `)}${this.formatWithColor(this.options.color.verbose, `${lValue} ->`)}`, this.formatWithColor(this.options.color.verbose, this.compactValue(rValue)));
    }
    /**
     * Logs the heritage of a ClassDeclaration
     */
    logHeritage(classDeclaration) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        const parent = Object.getPrototypeOf(classDeclaration);
        // eslint-disable-next-line @typescript-eslint/strict-boolean-expressions
        if (parent.toString().includes("[Class")) {
            console.log(`${this.formatWithColor(this.options.color.debug, classDeclaration.toString())} ${this.formatWithColor(this.options.color.debug, `extends`)} ${this.formatWithColor(this.options.color.debug, parent.toString())}`);
        }
    }
    /**
     * Logs the newest value has been pushed onto the Stack
     */
    logStack(stack) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(this.formatWithColor(this.options.color.debug, `Stack value: ${stringifyLiteral(this.compactValue(stack.lastItem))}`));
    }
    /**
     * Logs the entire Traversal Stack
     */
    logStatementTraversalStack(stack, typescript) {
        if (this.options.logLevel < LogLevelKind.DEBUG)
            return;
        console.log(this.formatWithColor(this.options.color.debug, `[${stack.map(kind => stringifySyntaxKind(kind, typescript)).join(", ")}]`));
    }
    /**
     * Makes a value compact so it is easier on the eyes when printing it
     */
    compactValue(value) {
        return inspect(value, { depth: 0, colors: true, compact: true, maxArrayLength: 5 });
    }
    formatWithColor(loggerColor, message) {
        return color[loggerColor](message);
    }
}

/**
 * Returns true if the given Node is an Expression.
 * Uses an internal non-exposed Typescript helper to decide whether or not the Node is an Expression
 */
function isExpression(node, typescript) {
    var _a;
    return typescript.isExpressionNode(node) || typescript.isIdentifier(node) || ((_a = typescript.isPrivateIdentifier) === null || _a === void 0 ? void 0 : _a.call(typescript, node));
}

/**
 * Returns true if the given Node is a Statement
 * Uses an internal non-exposed Typescript helper to decide whether or not the Node is an Expression
 */
function isStatement(node, typescript) {
    return typescript.isStatementButNotDeclaration(node);
}

/**
 * Creates a Stack
 *
 * @return
 */
function createStack() {
    const stack = [];
    return {
        /**
         * Gets an iterator for the Stack
         *
         * @return
         */
        [Symbol.iterator]() {
            return stack[Symbol.iterator]();
        },
        /**
         * Gets the length of the Stack
         *
         * @return
         */
        get length() {
            return stack.length;
        },
        /**
         * Gets the last item of the Stack
         *
         * @return
         */
        get lastItem() {
            return stack[stack.length - 1];
        },
        /**
         * Pushes the given StackEntries on to the Stack
         *
         * @param values
         * @return
         */
        push(...values) {
            return stack.push(...values);
        },
        /**
         * Pops the last item from the stack
         *
         * @return
         */
        pop() {
            return stack.pop();
        }
    };
}

/**
 * Reports an error
 */
function reportError(reporting, error, node) {
    // Report the error if a reporter is hooked up
    if (reporting.reportErrors != null && !reporting.reportedErrorSet.has(error)) {
        reporting.reportedErrorSet.add(error);
        reporting.reportErrors({
            error: error,
            node: error instanceof EvaluationError ? error.node : node
        });
    }
}

/**
 * Creates and returns a Set of Errors that has been seen and has been reported
 */
function createReportedErrorSet() {
    return new WeakSet();
}

/**
 * Will get a literal value for the given Expression, ExpressionStatement, or Declaration.
 */
function evaluate({ typeChecker, node, environment: { preset = "NODE", extra = {} } = {}, moduleOverrides = {}, typescript = TSModule, logLevel = LogLevelKind.SILENT, policy: { deterministic = false, network = false, console = false, maxOps = Infinity, maxOpDuration = Infinity, io = {
    read: true,
    write: false
}, process = {
    exit: false,
    spawnChild: false
} } = {}, reporting: reportingInput = {} }) {
    // Take the simple path first. This may be far more performant than building up an environment
    const simpleLiteralResult = evaluateSimpleLiteral(node, typescript);
    if (simpleLiteralResult.success)
        return simpleLiteralResult;
    // Otherwise, build an environment and get to work
    // Sanitize the evaluation policy based on the input options
    const policy = {
        deterministic,
        maxOps,
        maxOpDuration,
        network,
        console,
        io: {
            read: typeof io === "boolean" ? io : io.read,
            write: typeof io === "boolean" ? io : io.write
        },
        process: {
            exit: typeof process === "boolean" ? process : process.exit,
            spawnChild: typeof process === "boolean" ? process : process.spawnChild
        }
    };
    // Sanitize the Reporting options based on the input options
    const reporting = {
        ...reportingInput,
        reportedErrorSet: createReportedErrorSet()
    };
    /**
     * The error that has been thrown most recently.
     * We can' just throw errors internally, as some tools may patch error handling
     * and treat them as uncaught exceptions, which breaks the behavior of evaluate,
     * which never throws and instead returns a record with a {success: false, reason: Error} value.
     */
    let error;
    // Prepare a logger
    const logger = new Logger(logLevel);
    const throwError = ex => {
        // Report the Error
        reportError(reporting, ex, ex.node);
        error = ex;
        return error;
    };
    // Prepare the initial environment
    const environment = createLexicalEnvironment({
        inputEnvironment: {
            preset,
            extra
        },
        startingNode: node,
        policy
    });
    // Prepare a Stack
    const stack = createStack();
    const statementTraversalStack = createStatementTraversalStack();
    const nodeEvaluatorOptions = {
        policy,
        typeChecker,
        typescript,
        logger,
        stack,
        moduleOverrides,
        reporting,
        throwError,
        environment,
        statementTraversalStack,
        getCurrentError: () => error
    };
    // Prepare a NodeEvaluator
    const nodeEvaluator = createNodeEvaluator(nodeEvaluatorOptions);
    try {
        let value;
        if (isExpression(node, typescript)) {
            value = nodeEvaluator.expression(node, nodeEvaluatorOptions);
        }
        else if (isStatement(node, typescript)) {
            nodeEvaluator.statement(node, nodeEvaluatorOptions);
            value = stack.pop();
        }
        else if (isDeclaration(node, typescript)) {
            nodeEvaluator.declaration(node, nodeEvaluatorOptions);
            value = stack.pop();
        }
        // Otherwise, produce an UnexpectedNodeError
        else {
            throwError(new UnexpectedNodeError({ node, environment, typescript }));
        }
        if (error != null) {
            return {
                success: false,
                reason: error
            };
        }
        else {
            // Log the value before returning
            logger.logResult(value);
            return {
                success: true,
                value
            };
        }
    }
    catch (reason) {
        throwError(reason);
        return {
            success: false,
            reason: reason
        };
    }
}

export { EvaluationError, IoError, LogLevelKind, MaxOpDurationExceededError, MaxOpsExceededError, MissingCatchOrFinallyAfterTryError, ModuleNotFoundError, NetworkError, NonDeterministicError, NotCallableError, PolicyError, ProcessError, UndefinedIdentifierError, UndefinedLeftValueError, UnexpectedNodeError, UnexpectedSyntaxError, evaluate, isEvaluationError };
//# sourceMappingURL=index.js.map
