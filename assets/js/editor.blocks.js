/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__login__ = __webpack_require__(1);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__login___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__login__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__online_giving__ = __webpack_require__(2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__online_giving___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__online_giving__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__group_info__ = __webpack_require__(3);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__group_info___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__group_info__);\n/**\n * Import CCBPress blocks\n */\n\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Jsb2Nrcy9pbmRleC5qcz84MTkzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogSW1wb3J0IENDQlByZXNzIGJsb2Nrc1xuICovXG5pbXBvcnQgJy4vbG9naW4nO1xuaW1wb3J0ICcuL29ubGluZS1naXZpbmcnO1xuaW1wb3J0ICcuL2dyb3VwLWluZm8nO1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vYmxvY2tzL2luZGV4LmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/***/ (function(module, exports) {

eval("throw new Error(\"Module build failed: SyntaxError: Unexpected token (42:6)\\n\\n\\u001b[0m \\u001b[90m 40 | \\u001b[39m  edit\\u001b[33m:\\u001b[39m props \\u001b[33m=>\\u001b[39m {\\n \\u001b[90m 41 | \\u001b[39m    \\u001b[36mreturn\\u001b[39m (\\n\\u001b[31m\\u001b[1m>\\u001b[22m\\u001b[39m\\u001b[90m 42 | \\u001b[39m      \\u001b[33m<\\u001b[39m\\u001b[33mdiv\\u001b[39m className\\u001b[33m=\\u001b[39m{props\\u001b[33m.\\u001b[39mclassName} title\\u001b[33m=\\u001b[39m\\u001b[32m\\\"title\\\"\\u001b[39m\\u001b[33m>\\u001b[39m\\n \\u001b[90m    | \\u001b[39m      \\u001b[31m\\u001b[1m^\\u001b[22m\\u001b[39m\\n \\u001b[90m 43 | \\u001b[39m        \\u001b[33m<\\u001b[39m\\u001b[33mform\\u001b[39m \\u001b[36mclass\\u001b[39m\\u001b[33m=\\u001b[39m\\u001b[32m\\\"ccbpress-core-login\\\"\\u001b[39m method\\u001b[33m=\\u001b[39m\\u001b[32m\\\"post\\\"\\u001b[39m target\\u001b[33m=\\u001b[39m\\u001b[32m\\\"_blank\\\"\\u001b[39m\\u001b[33m>\\u001b[39m\\n \\u001b[90m 44 | \\u001b[39m          \\u001b[33m<\\u001b[39m\\u001b[33mfieldset\\u001b[39m disabled\\u001b[33m=\\u001b[39m\\u001b[32m\\\"true\\\"\\u001b[39m\\u001b[33m>\\u001b[39m\\n \\u001b[90m 45 | \\u001b[39m            \\u001b[33m<\\u001b[39m\\u001b[33mlabel\\u001b[39m\\u001b[33m>\\u001b[39m{__(\\u001b[32m\\\"Username:\\\"\\u001b[39m)}\\u001b[33m<\\u001b[39m\\u001b[33m/\\u001b[39m\\u001b[33mlabel\\u001b[39m\\u001b[33m>\\u001b[39m\\u001b[0m\\n\");//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOltdLCJtYXBwaW5ncyI6IiIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/***/ (function(module, exports) {

eval("throw new Error(\"Module build failed: SyntaxError: Unexpected token (50:6)\\n\\n\\u001b[0m \\u001b[90m 48 | \\u001b[39m  edit({ className }) {\\n \\u001b[90m 49 | \\u001b[39m    \\u001b[36mreturn\\u001b[39m (\\n\\u001b[31m\\u001b[1m>\\u001b[22m\\u001b[39m\\u001b[90m 50 | \\u001b[39m      \\u001b[33m<\\u001b[39m\\u001b[33mdiv\\u001b[39m className\\u001b[33m=\\u001b[39m{className}\\u001b[33m>\\u001b[39m\\n \\u001b[90m    | \\u001b[39m      \\u001b[31m\\u001b[1m^\\u001b[22m\\u001b[39m\\n \\u001b[90m 51 | \\u001b[39m        \\u001b[33m<\\u001b[39m\\u001b[33mform\\u001b[39m\\u001b[33m>\\u001b[39m\\n \\u001b[90m 52 | \\u001b[39m          \\u001b[33m<\\u001b[39m\\u001b[33mfieldset\\u001b[39m disabled\\u001b[33m=\\u001b[39m\\u001b[32m\\\"true\\\"\\u001b[39m\\u001b[33m>\\u001b[39m\\n \\u001b[90m 53 | \\u001b[39m            \\u001b[33m<\\u001b[39m\\u001b[33minput\\u001b[39m type\\u001b[33m=\\u001b[39m\\u001b[32m\\\"submit\\\"\\u001b[39m value\\u001b[33m=\\u001b[39m{__(\\u001b[32m\\\"Give Now\\\"\\u001b[39m)} \\u001b[33m/\\u001b[39m\\u001b[33m>\\u001b[39m\\u001b[0m\\n\");//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMi5qcyIsInNvdXJjZXMiOltdLCJtYXBwaW5ncyI6IiIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/***/ (function(module, exports) {

eval("throw new Error(\"Module build failed: SyntaxError: Unexpected token (85:4)\\n\\n\\u001b[0m \\u001b[90m 83 | \\u001b[39m\\t\\t\\u001b[36mfor\\u001b[39m (\\u001b[36mconst\\u001b[39m prop \\u001b[36min\\u001b[39m phones) {\\n \\u001b[90m 84 | \\u001b[39m\\t\\t\\tphones_array\\u001b[33m.\\u001b[39mpush(\\n\\u001b[31m\\u001b[1m>\\u001b[22m\\u001b[39m\\u001b[90m 85 | \\u001b[39m\\t\\t\\t\\t\\u001b[33m<\\u001b[39m\\u001b[33mdiv\\u001b[39m \\u001b[36mclass\\u001b[39m\\u001b[33m=\\u001b[39m\\u001b[32m\\\"ccbpress-group-info-leader-phone\\\"\\u001b[39m\\u001b[33m>\\u001b[39m{phones[prop]}\\u001b[33m<\\u001b[39m\\u001b[33m/\\u001b[39m\\u001b[33mdiv\\u001b[39m\\u001b[33m>\\u001b[39m\\u001b[33m,\\u001b[39m\\n \\u001b[90m    | \\u001b[39m\\t\\t\\t\\t\\u001b[31m\\u001b[1m^\\u001b[22m\\u001b[39m\\n \\u001b[90m 86 | \\u001b[39m\\t\\t\\t)\\u001b[33m;\\u001b[39m\\n \\u001b[90m 87 | \\u001b[39m\\t\\t}\\n \\u001b[90m 88 | \\u001b[39m\\u001b[0m\\n\");//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMy5qcyIsInNvdXJjZXMiOltdLCJtYXBwaW5ncyI6IiIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///3\n");

/***/ })
/******/ ]);