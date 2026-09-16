"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    var desc = Object.getOwnPropertyDescriptor(m, k);
    if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
      desc = { enumerable: true, get: function() { return m[k]; } };
    }
    Object.defineProperty(o, k2, desc);
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __importStar = (this && this.__importStar) || (function () {
    var ownKeys = function(o) {
        ownKeys = Object.getOwnPropertyNames || function (o) {
            var ar = [];
            for (var k in o) if (Object.prototype.hasOwnProperty.call(o, k)) ar[ar.length] = k;
            return ar;
        };
        return ownKeys(o);
    };
    return function (mod) {
        if (mod && mod.__esModule) return mod;
        var result = {};
        if (mod != null) for (var k = ownKeys(mod), i = 0; i < k.length; i++) if (k[i] !== "default") __createBinding(result, mod, k[i]);
        __setModuleDefault(result, mod);
        return result;
    };
})();
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var __param = (this && this.__param) || function (paramIndex, decorator) {
    return function (target, key) { decorator(target, key, paramIndex); }
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.PemesananController = exports.disposisiStorage = void 0;
const common_1 = require("@nestjs/common");
const platform_express_1 = require("@nestjs/platform-express");
const multer_1 = require("multer");
const path_1 = require("path");
const pemesanan_service_1 = require("./pemesanan.service");
const jwt_auth_guard_1 = require("../auth/jwt-auth.guard");
const create_pemesanan_dto_1 = require("./dto/create-pemesanan.dto");
const fs = __importStar(require("fs"));
exports.disposisiStorage = (0, multer_1.diskStorage)({
    destination: (_req, _file, cb) => {
        const dest = (0, path_1.join)(process.cwd(), '..', 'storage', 'app', 'public', 'disposisi');
        if (!fs.existsSync(dest)) {
            fs.mkdirSync(dest, { recursive: true });
        }
        cb(null, dest);
    },
    filename: (_req, file, cb) => {
        const uniqueSuffix = `${Date.now()}-${Math.round(Math.random() * 1e9)}`;
        cb(null, `disposisi-${uniqueSuffix}${(0, path_1.extname)(file.originalname)}`);
    },
});
let PemesananController = class PemesananController {
    constructor(pemesananService) {
        this.pemesananService = pemesananService;
    }
    index(req, query) {
        return this.pemesananService.index(req.user.id, query);
    }
    checkConflict(query) {
        return this.pemesananService.checkConflictApi(query);
    }
    getUnits() {
        return this.pemesananService.getUnits();
    }
    show(id, req) {
        return this.pemesananService.show(id, req.user);
    }
    store(dto, req, file) {
        const filePath = file ? `disposisi/${file.filename}` : undefined;
        return this.pemesananService.store(dto, req.user, filePath);
    }
    cancel(id, req) {
        return this.pemesananService.cancel(id, req.user);
    }
    selesaiAwal(id, req) {
        return this.pemesananService.selesaiAwal(id, req.user);
    }
};
exports.PemesananController = PemesananController;
__decorate([
    (0, common_1.Get)(),
    __param(0, (0, common_1.Req)()),
    __param(1, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object, Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "index", null);
__decorate([
    (0, common_1.Get)('check-conflict'),
    __param(0, (0, common_1.Query)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "checkConflict", null);
__decorate([
    (0, common_1.Get)('units'),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", []),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "getUnits", null);
__decorate([
    (0, common_1.Get)(':id'),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "show", null);
__decorate([
    (0, common_1.Post)(),
    (0, common_1.UseInterceptors)((0, platform_express_1.FileInterceptor)('file_disposisi', { storage: exports.disposisiStorage })),
    __param(0, (0, common_1.Body)()),
    __param(1, (0, common_1.Req)()),
    __param(2, (0, common_1.UploadedFile)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [create_pemesanan_dto_1.CreatePemesananDto, Object, Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "store", null);
__decorate([
    (0, common_1.Post)(':id/cancel'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "cancel", null);
__decorate([
    (0, common_1.Post)(':id/selesai-awal'),
    (0, common_1.HttpCode)(common_1.HttpStatus.OK),
    __param(0, (0, common_1.Param)('id', common_1.ParseIntPipe)),
    __param(1, (0, common_1.Req)()),
    __metadata("design:type", Function),
    __metadata("design:paramtypes", [Number, Object]),
    __metadata("design:returntype", void 0)
], PemesananController.prototype, "selesaiAwal", null);
exports.PemesananController = PemesananController = __decorate([
    (0, common_1.Controller)('pemesanan'),
    (0, common_1.UseGuards)(jwt_auth_guard_1.JwtAuthGuard),
    __metadata("design:paramtypes", [pemesanan_service_1.PemesananService])
], PemesananController);
//# sourceMappingURL=pemesanan.controller.js.map