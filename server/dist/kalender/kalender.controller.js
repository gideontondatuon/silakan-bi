"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.KalenderController = void 0;
const common_1 = require("@nestjs/common");
const kalender_service_1 = require("./kalender.service");
const jwt_auth_guard_1 = require("../auth/jwt-auth.guard");
let KalenderController = class KalenderController {
    constructor(kalenderService) {
        this.kalenderService = kalenderService;
    }
    index(req, query) { return this.kalenderService.index(req.user.id, query); }
    events(req, query) { return this.kalenderService.events(req.user.id, query); }
};
exports.KalenderController = KalenderController;
__decorate([
    (0, common_1.Get)(),
    __param(0, (0, common_1.Req)()),
    __param(1, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object, Object]),
    __metadata("design:returntype", void 0)
], KalenderController.prototype, "index", null);
__decorate([
    (0, common_1.Get)('events'),
    __param(0, (0, common_1.Req)()),
    __param(1, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object, Object]),
    __metadata("design:returntype", void 0)
], KalenderController.prototype, "events", null);
exports.KalenderController = KalenderController = __decorate([
    (0, common_1.Controller)('kalender'),
    (0, common_1.UseGuards)(jwt_auth_guard_1.JwtAuthGuard),
    __metadata("design:paramtypes", [kalender_service_1.KalenderService])
], KalenderController);
//# sourceMappingURL=kalender.controller.js.map