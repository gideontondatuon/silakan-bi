"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.HariLiburModule = void 0;
const common_1 = require("@nestjs/common");
const hari_libur_controller_1 = require("./hari-libur.controller");
const hari_libur_service_1 = require("./hari-libur.service");
let HariLiburModule = class HariLiburModule {
};
exports.HariLiburModule = HariLiburModule;
exports.HariLiburModule = HariLiburModule = __decorate([
    (0, common_1.Module)({ controllers: [hari_libur_controller_1.HariLiburController], providers: [hari_libur_service_1.HariLiburService] })
], HariLiburModule);
//# sourceMappingURL=hari-libur.module.js.map