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
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__less_calista_less__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__less_calista_less___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__less_calista_less__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__src_drupal__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__src_drupal___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__src_drupal__);





/***/ }),
/* 1 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

Object.defineProperty(exports, "__esModule", { value: true });
var pane_1 = __webpack_require__(3);
var page_1 = __webpack_require__(4);
__webpack_require__(5);
Drupal.behaviors.calistaPage = {
    attach: function (context, settings) {
        for (var _i = 0, _a = context.querySelectorAll("[data-page]:not([data-page-initialized])"); _i < _a.length; _i++) {
            var element = _a[_i];
            new page_1.Page(element);
        }
    }
};
Drupal.behaviors.calistaPane = {
    attach: function (context, settings) {
        for (var _i = 0, _a = context.querySelectorAll("#contextual-pane"); _i < _a.length; _i++) {
            var element = _a[_i];
            new pane_1.Pane(element);
        }
    }
};


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var Tab = (function () {
    function Tab(element, linkElement, name) {
        this.element = element;
        this.linkElement = linkElement;
        this.name = name;
    }
    return Tab;
}());
var PaneState = (function () {
    function PaneState() {
    }
    PaneState.prototype.isHidden = function () {
        return (new RegExp('calista-pane-hidden=1')).test(document.cookie);
    };
    PaneState.prototype.hide = function () {
        document.cookie = "calista-pane-hidden=1";
    };
    PaneState.prototype.show = function () {
        document.cookie = "calista-pane-hidden=0";
    };
    return PaneState;
}());
var Pane = (function () {
    function Pane(element) {
        var _this = this;
        this.displayed = true;
        this.element = element;
        this.state = new PaneState();
        var toggleLink = element.querySelector("#contextual-pane-toggle a");
        toggleLink.addEventListener("click", function (event) {
            event.stopPropagation();
            event.preventDefault();
            _this.togglePane();
            toggleLink.blur();
        });
        this.restoreInitialState();
        this.refresh();
        var defaultTab = element.getAttribute('data-active-tab') || "";
        if (defaultTab) {
            this.toggleTab(defaultTab);
        }
    }
    Pane.prototype.restoreInitialState = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _this = this;
            return __generator(this, function (_a) {
                this.element.classList.add("initial-collapse");
                setTimeout(function () {
                    _this.element.classList.remove("initial-collapse");
                }, 1000);
                if (this.state.isHidden()) {
                    this.displayed = false;
                    this.element.classList.add("contextual-collapsed");
                }
                else {
                    this.displayed = true;
                    this.element.classList.remove("contextual-collapsed");
                }
                return [2];
            });
        });
    };
    Pane.prototype.refresh = function () {
        return __awaiter(this, void 0, void 0, function () {
            var _this = this;
            var _loop_1, this_1, _i, _a, linkElement;
            return __generator(this, function (_b) {
                this.tabs = [];
                _loop_1 = function (linkElement) {
                    var tabName = linkElement.getAttribute("data-tab-toggle") || "";
                    var tabElement = this_1.element.querySelector("[data-tab=" + tabName + "]");
                    if (!tabElement) {
                        linkElement.setAttribute("disabled", "disabled");
                        linkElement.classList.add("disabled");
                        linkElement.classList.remove("active");
                        return "continue";
                    }
                    this_1.tabs.push(new Tab(tabElement, linkElement, tabName));
                    linkElement.addEventListener("click", function (event) {
                        event.stopPropagation();
                        event.preventDefault();
                        _this.toggleTab(tabName);
                    });
                };
                this_1 = this;
                for (_i = 0, _a = this.element.querySelectorAll("[data-tab-toggle]"); _i < _a.length; _i++) {
                    linkElement = _a[_i];
                    _loop_1(linkElement);
                }
                return [2];
            });
        });
    };
    Pane.prototype.togglePane = function () {
        return __awaiter(this, void 0, void 0, function () {
            return __generator(this, function (_a) {
                if (this.displayed) {
                    this.element.classList.add("contextual-collapsed");
                    this.displayed = false;
                    this.state.hide();
                }
                else {
                    this.element.classList.remove("contextual-collapsed");
                    this.displayed = true;
                    this.state.show();
                }
                return [2];
            });
        });
    };
    Pane.prototype.toggleTab = function (name) {
        return __awaiter(this, void 0, void 0, function () {
            var activeTab, _i, _a, tab;
            return __generator(this, function (_b) {
                console.log("contextual pane: toggled tab: " + name);
                activeTab = null;
                for (_i = 0, _a = this.tabs; _i < _a.length; _i++) {
                    tab = _a[_i];
                    if (tab.name === name) {
                        activeTab = tab;
                    }
                    else {
                        tab.element.classList.remove("active");
                        tab.linkElement.classList.remove("active");
                    }
                }
                if (activeTab) {
                    activeTab.element.classList.add("active");
                    activeTab.linkElement.classList.add("active");
                }
                else {
                    console.log("contextual pane: could not find tab: " + name);
                }
                return [2];
            });
        });
    };
    return Pane;
}());
exports.Pane = Pane;


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : new P(function (resolve) { resolve(result.value); }).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = y[op[0] & 2 ? "return" : op[0] ? "throw" : "next"]) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [0, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
Object.defineProperty(exports, "__esModule", { value: true });
var REFRESH_URL = 'admin/calista/refresh';
function parseLink(uri) {
    if (uri === "") {
        return {};
    }
    var pos = uri.indexOf('?');
    if (-1 !== pos) {
        uri = uri.substr(pos + 1);
    }
    else {
        return {};
    }
    var ret = {};
    uri.split("&").forEach(function (raw) {
        var pos = raw.indexOf("=");
        if (-1 === pos) {
            ret[raw] = "";
        }
        else {
            var key = raw.substr(0, pos);
            var value = raw.substr(pos + 1);
            ret[key] = decodeURIComponent(value.replace(/\+/g, " "));
        }
    });
    return ret;
}
function createParamString(query) {
    var ret = [];
    for (var key in query) {
        ret.push(encodeURIComponent(key) + '=' + encodeURIComponent(query[key]));
    }
    return ret.join("&");
}
function debounce(func, wait, immediate) {
    if (immediate === void 0) { immediate = false; }
    var timeout;
    return function () {
        var context = this;
        var args = arguments;
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(function () {
            timeout = null;
            if (!immediate) {
                func.apply(context, args);
            }
        }, wait);
        if (callNow) {
            func.apply(context, args);
        }
    };
}
;
var Page = (function () {
    function Page(element) {
        this.ajaxOnly = false;
        this.baseUrl = "/";
        this.refreshing = false;
        this.element = element;
        this.element.setAttribute("data-page-initialized", "1");
        if (element.hasAttribute("data-page-query")) {
            var value = element.getAttribute("data-page-query");
            try {
                this.query = JSON.parse(value);
            }
            catch (error) {
                console.log("invalid JSON: " + value);
                this.query = {};
            }
        }
        this.route = element.getAttribute('data-page-route');
        this.id = element.getAttribute('data-page');
        this.viewType = element.getAttribute('data-view-type');
        this.searchParam = element.getAttribute('data-page-search');
        this.ajaxOnly = element.hasAttribute('data-ajax-only');
        this.modal = document.createElement('div');
        this.modal.setAttribute("class", "page-modal");
        this.element.insertBefore(this.modal, this.element.firstChild);
        this.attachBehaviors();
    }
    Page.prototype.request = function (route, data) {
        var _this = this;
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', _this.baseUrl + route + "?" + createParamString(data));
            req.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            req.addEventListener("load", function () {
                if (this.status !== 200) {
                    reject(this.status + ": " + this.statusText);
                }
                else {
                    resolve(req);
                }
            });
            req.addEventListener("error", function () {
                reject(this.status + ": " + this.statusText);
            });
            req.send();
        });
    };
    Page.prototype.placePageBlocks = function (response) {
        if (response.query) {
            this.query = response.query;
        }
        if (response.blocks) {
            for (var index in response.blocks) {
                var count = 0;
                for (var _i = 0, _a = this.element.querySelectorAll("[data-page-block=" + index + "]"); _i < _a.length; _i++) {
                    var block = _a[_i];
                    block.innerHTML = response.blocks[index];
                    count++;
                }
                if (count) {
                    if (1 < count) {
                        console.log("Warning, block " + index + " exists more than once in page");
                    }
                    this.attachBehaviors();
                }
                else {
                    console.log("Warning, block " + index + " does not exists in page");
                }
            }
        }
    };
    Page.prototype.reload = function (url, error) {
        if (error) {
            console.log(error);
        }
        window.location.href = url;
    };
    Page.prototype.modalSpawn = function () {
        this.modal.classList.add("loading");
    };
    Page.prototype.modalDestroy = function () {
        this.modal.classList.remove("loading");
    };
    Page.prototype.refreshPage = function (query, dropAll) {
        if (dropAll === void 0) { dropAll = false; }
        return __awaiter(this, void 0, void 0, function () {
            var _this = this;
            var newUrl, data, key, key;
            return __generator(this, function (_a) {
                if (this.refreshing) {
                    return [2];
                }
                this.refreshing = true;
                newUrl = location.pathname + "?" + createParamString(query);
                if (this.viewType === 'twig_form_page') {
                    this.reload(newUrl);
                    return [2];
                }
                this.modalSpawn();
                data = {};
                data._page_id = this.id;
                data._route = this.route;
                if (!dropAll) {
                    for (key in this.query) {
                        data[key] = this.query[key];
                    }
                }
                if (query) {
                    for (key in query) {
                        data[key] = query[key];
                    }
                }
                this.request(REFRESH_URL, data).then(function (req) {
                    var response = JSON.parse(req.responseText);
                    if (!response) {
                        throw req.status + ": " + req.statusText + ": got invalid response data";
                    }
                    _this.placePageBlocks(response);
                    _this.refreshing = false;
                    _this.modalDestroy();
                }, function (error) {
                    _this.reload(newUrl, error);
                }).catch(function (error) {
                    _this.reload(newUrl, error);
                });
                return [2];
            });
        });
    };
    Page.prototype.attachBehaviors = function () {
        var _this = this;
        var target = this;
        Drupal.attachBehaviors(this.element);
        var _loop_1 = function (link) {
            link.addEventListener("click", function (event) {
                event.stopPropagation();
                event.preventDefault();
                target.refreshPage(parseLink(link.getAttribute("href") || ""), true);
            });
        };
        for (var _i = 0, _a = this.element.querySelectorAll("[data-page-link]"); _i < _a.length; _i++) {
            var link = _a[_i];
            _loop_1(link);
        }
        var form = this.element.querySelector("form.calista-search-form");
        if (form) {
            var searchInput_1 = form.querySelector("input[type=text]");
            if (searchInput_1) {
                var typeListener = debounce(function (event) {
                    var query = {};
                    query[_this.searchParam || "s"] = searchInput_1.value;
                    _this.refreshPage(query);
                }, 500);
                searchInput_1.addEventListener("keydown", typeListener);
                searchInput_1.addEventListener("keypress", typeListener);
                searchInput_1.addEventListener("change", typeListener);
            }
            form.addEventListener("submit", function (event) {
                event.stopPropagation();
                event.preventDefault();
                if (searchInput_1) {
                    var query = {};
                    query[_this.searchParam || "s"] = searchInput_1.value;
                    _this.refreshPage(query);
                }
            });
        }
        var master = this.element.querySelector('[data-page-checkbox="all"]');
        if (master) {
            var checkboxes_1 = this.element.querySelectorAll("table input:checkbox");
            if (checkboxes_1.length) {
                master.addEventListener("click", function (event) {
                    event.stopPropagation();
                    for (var _i = 0, checkboxes_2 = checkboxes_1; _i < checkboxes_2.length; _i++) {
                        var checkbox = checkboxes_2[_i];
                        checkbox.checked = master.checked;
                    }
                });
            }
        }
    };
    return Page;
}());
exports.Page = Page;


/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";



/***/ })
/******/ ]);
//# sourceMappingURL=calista.js.map