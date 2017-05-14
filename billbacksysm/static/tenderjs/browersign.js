function billSign() 
{
    this.Request = proto.lookup("hhmix.Request");
    this.WriteRequest = proto.lookup("hhmix.WriteRequest");

    this.MessageType = proto.lookup("hhmix.MessageType");
    this.UserType = proto.lookup("hhmix.User_Type");
    this.AdminType = proto.lookup("hhmix.Admin_Type");

    this.RequestSetAdmin = proto.lookup("hhmix.RequestSetAdmin");
    this.RequestNoteData = proto.lookup("hhmix.RequestNoteData");
    this.RequestUserData = proto.lookup("hhmix.RequestUserData");
    this.RequestBankRmb = proto.lookup("hhmix.RequestBankRmb");
    this.RequestSetAdmin = proto.lookup("hhmix.RequestSetAdmin");
    this.RequestUserAddress = proto.lookup("hhmix.RequestUserAddress");
    this.RequestNotes = proto.lookup("hhmix.RequestNotes");
    this.RequestIncreaseBankRmb = proto.lookup("hhmix.RequestIncreaseBankRmb");
    this.RequestUserRegister = proto.lookup("hhmix.RequestUserRegister");
    this.RequestSetDepositRate = proto.lookup("hhmix.RequestSetDepositRate");
    this.RequestUserWithdraw = proto.lookup("hhmix.RequestUserWithdraw");
    this.RequestUserDeposit = proto.lookup("hhmix.RequestUserDeposit");
    this.RequestCreateNote = proto.lookup("hhmix.RequestCreateNote");
    this.RequestSellNote = proto.lookup("hhmix.RequestSellNote");
    this.RequestOperateNote = proto.lookup("hhmix.RequestOperateNote");
    this.RequestTransferAsset = proto.lookup("hhmix.RequestTransferAsset");
    this.RequestDeleteNote = proto.lookup("hhmix.RequestDeleteNote");
    this.RequestDiscountNote = proto.lookup("hhmix.RequestDiscountNote");
    this.RequestEndorseNote = proto.lookup("hhmix.RequestEndorseNote");
    this.RequestEditNote = proto.lookup("hhmix.RequestEditNote");
    this.RequestAddFinaceCheck = proto.lookup("hhmix.RequestAddFinaceCheck");

    this.getInstructionId = function()
    {
        var ins = [];
        var low    = new Buffers(nacl.randomBytes(4)).readIntLE(0, 3);
        var height = new Buffers(nacl.randomBytes(4)).readIntLE(0, 3);
        var i = new dcodeIO.Long(low, height);
        ins.push(i.toString());
        return i;
    }

    this.getUserType = function(userType)
    {
        var typeValue;
        switch(userType)
        {
            case 1:
                //外部公司
                typeValue = this.UserType.values.EXTERNAL;
                break;
            case 2:
                //成员公司
                typeValue = this.UserType.values.MEMBER;
                break;
            case 3:
                //财务公司
                typeValue = this.UserType.values.FINANCE;
                break;
            default:
                //未知身份
                typeValue = this.UserType.values.USER_UNK;
                break;
        }
        return typeValue;
    }

    this.getAdminType = function(adminType)
    {
        var typeValue;
        switch(adminType)
        {
            case 1:
                //管理员
                typeValue = this.AdminType.values.NORMAL;
                break;
            case 2:
                //财务公司
                typeValue = this.AdminType.values.FINANCIAL;
                break;
            case 3:
                //复核公司
                typeValue = this.AdminType.values.CHECK;
                break;
            case 4:
                //后台审核管理员
                typeValue = this.AdminType.values.CREATOR;
                break;
            case 5:
                //外部公司创建管理员
                typeValue = this.AdminType.values.REGISTER;
                break;
            case 9:
                //外部公司创建管理员
                typeValue = this.AdminType.values.DEVELOP;
                break;
            default:
                //未知身份
                typeValue = this.AdminType.values.ADMIN_UNK;
                break;
        }
        return typeValue;
    }

    this.getBillInfoType = function(infoType)
    {
        var typeValue;
        switch(infoType)
        {
            case 1:
                //获取票据总额
                typeValue = this.MessageType.values.MsgGetNoteTotal;
                break;
            case 2:
                //获取票据状态
                typeValue = this.MessageType.values.MsgGetNoteState;
                break;
            case 3:
                //获取票据卖出时间
                typeValue = this.MessageType.values.MsgGetNoteSoldTime;
                break;
            case 4:
                //获取票据相关用户地址
                typeValue = this.MessageType.values.MsgGetNoteAddress;
                break;
            case 5:
                //获取票据标志值
                typeValue = this.MessageType.values.MsgGetNoteFlag;
                break;
            case 6:
                //获取票据相关数据
                typeValue = this.MessageType.values.MsgGetNoteNumber;
                break;
            default:
                //获取票据信息
                typeValue = this.MessageType.values.MsgGetNoteInfo;
                break;
        }
        return typeValue;
    }

    this.getUserInfoType = function(infoType)
    {
        var typeValue;
        switch(infoType){
            case 1:
                //获取用户财产信息
                typeValue = this.MessageType.values.MsgGetUserAsset;
                break;
            case 2:
                //获取用户保证金比率
                typeValue = this.MessageType.values.MsgGetUserDeposit;
                break;
            default:
                //获取用户uid
                typeValue = this.MessageType.values.MsgGetUserUID;
                break;
        }
        return typeValue;
    }

    this.getOperationType =function(operation)
    {
        var typeValue;
        switch(operation){
            case 'cancelSell':
                //取消挂牌
                typeValue = this.MessageType.values.MsgCancelSelling;
                break;
            case 'buy':
                //摘牌
                typeValue = this.MessageType.values.MsgInvestNote;
                break;
            case 'confirmSend':
                //确认发票
                typeValue = this.MessageType.values.MsgConfirmSending;
                break;
            case 'confirmReceive':
                //确认收票
                typeValue = this.MessageType.values.MsgConfirmReceiving;
                break;
            case 'confirmReceive':
                //系统确认收票
                typeValue = this.MessageType.values.MsgSystemConfirmReceiving;
                break;
            case 'cancelBuyBySystem':
                //系统取消买入
                typeValue = this.MessageType.values.MsgSystemCancelBuying;
                break;
            case 'cancelBuyByBuyer':
                //买方发起撤单
                typeValue = this.MessageType.values.MsgBuyerCancelBuying;
                break;
            case 'cancelBuyBySeller':
                //卖方取消买入
                typeValue = this.MessageType.values.MsgSellerCancelBuying;
                break;
            case 'cancelCancelByBuyer':
                //买方取消撤单
                typeValue = this.MessageType.values.MsgBuyerCancelCancel;
                break;
            case 'cancelBySystem':
                //系统撤单
                typeValue = this.MessageType.values.MsgSystemCancelOrder;
                break;
            case 'destroy':
                //销毁
                typeValue = this.MessageType.values.MsgDestroyNote;
                break;
        }
        return typeValue;
    }
}

/**
 * 获取公钥
 * @param   string  password    用户密码
 * @param   string  randnum     用户随机数
 * @return  string  用户公钥
 */
billSign.prototype.getPrivatekey = function(password,randnum)
{
    var string = password + randnum;
    var privkey = keccak_256(string);
    return privkey;
}

/**
 * 获取公钥
 * @param   string  userkey  用户私钥
 * @return  string  用户公钥
 */
billSign.prototype.getPublicKey = function(userkey)
{
    var seed = nacl.sign.keyPair.fromSeed(userkey);
    var publicKey = new Buffers(seed.publicKey).toString("hex");
    return publicKey;
}

/**
 * 设置管理员
 * @param  bytes    amdminMixkey        操作管理员混合私钥
 * @param  string   newAdminPublickey   新管理员公钥
 * @param  int      type                管理员类型 0:未知 1:管理员 2:财务公司 3:复核公司 4:原始管理员
 * @return object
 */
billSign.prototype.signSetAdmin = function(amdminMixkey, newAdminPublickey, type)
{
    var operation = this.RequestSetAdmin.create();
    type = type || 0;
    operation.admin = new Buffers(newAdminPublickey,'hex');
    operation.type = this.getAdminType(type);
    operation.actionId = this.MessageType.values.MsgSetAdmin;
    operation.uid = amdminMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestSetAdmin.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.setAdmin = operation;
    request.sign = nacl.sign(data, amdminMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 添加复核关系
 * @param  bytes    operatorMixkey      操作者混合私钥
 * @param  string   finacePublickey     财务公司公钥
 * @param  string   checkPublickey      复核公司公钥
 * @return object
 */
billSign.prototype.signAddFinaceCheck = function(operatorMixkey, finacePublickey, checkPublickey)
{
    var operation = this.RequestAddFinaceCheck.create();
    operation.finace = new Buffers(finacePublickey,'hex');
    operation.check = new Buffers(checkPublickey,'hex');
    operation.actionId = this.MessageType.values.MsgAddFinaceCheck;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestAddFinaceCheck.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.addFinaceCheck = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 用户注册
 * @param  bytes    adminMixkey 操作管理员混合私钥
 * @param  string   userPublickey  用户公钥
 * @param  int      type           管理员类型 0:未知 1:外部公司 2:成员公司 3:财务公司
 * @return object
 */
billSign.prototype.signUserRegister = function(adminMixkey, userPublickey, uid, type)
{
    var operation = this.RequestUserRegister.create();
    type = type || 0;
    operation.userAddress = new Buffers(userPublickey,'hex');
    operation.userUid = uid;
    operation.type = this.getUserType(type);
    operation.actionId = this.MessageType.values.MsgUserRegister;
    operation.uid = adminMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestUserRegister.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.userRegister = operation;
    request.sign = nacl.sign(data, adminMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 获取票据信息
 * @param  bytes    operatorMixkey 操作用户混合私钥
 * @param  string   billId         票据id
 * @param  string   infoType       信息类型
 * @return object
 */
billSign.prototype.signNoteData = function(operatorMixkey, billId, infoType)
{
    var operation = this.RequestNoteData.create();
    infoType = infoType || 0;
    operation.id = billId;
    operation.actionId = this.getBillInfoType(infoType);
    operation.uid = operatorMixkey.slice(32);
    var data = this.RequestNoteData.encode(operation).finish();
    var request = this.Request.create();
    request.noteData = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.Request.encode(request).finish()).toString("hex");
    var result = {signdata:signdata,sid:''};
    return result;
}

/**
 * 获取用户信息
 * @param  bytes    operatorMixkey 操作用户混合私钥
 * @param  string   userPublickey  用户公钥
 * @param  string   infoType       信息类型
 * @return object
 */
billSign.prototype.signUserData = function(operatorMixkey, userPublickey, infoType)
{
    var operation = this.RequestUserData.create();
    infoType = infoType || 0;
    operation.actionId = this.getUserInfoType(infoType);
    operation.user = new Buffers(userPublickey,'hex');
    operation.uid = operatorMixkey.slice(32);
    var data = this.RequestUserData.encode(operation).finish();
    var request = this.Request.create();
    request.userData = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.Request.encode(request).finish()).toString("hex");
    var result = {signdata:signdata,sid:''};
    return result;
}

/**
 * 获取银行人民币余额
 * @param  bytes    operatorMixkey 操作用户混合私钥
 * @param  string   bankPublickey  银行公钥
 * @return object
 */
billSign.prototype.signBankRmb = function(operatorMixkey, bankPublickey)
{
    var operation = this.RequestBankRmb.create();
    operation.bank = new Buffers(bankPublickey,'hex');
    operation.actionId = this.MessageType.values.MsgGetBankRmb;
    operation.uid = operatorMixkey.slice(32);
    var data = this.RequestBankRmb.encode(operation).finish();
    var request = this.Request.create();
    request.bankRmb = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.Request.encode(request).finish()).toString("hex");
    var result = {signdata:signdata,sid:''};
    return result;
}

/**
 * 获取用户公钥地址
 * @param  bytes    operatorMixkey  操作用户混合私钥
 * @param  int64    uid             用户id
 * @return object
 */
billSign.prototype.signUserAddress = function(operatorMixkey, uid)
{
    var operation = this.RequestUserAddress.create();
    operation.userUid = uid;
    operation.actionId = this.MessageType.values.MsgGetUserAddress;
    operation.uid = operatorMixkey.slice(32);
    var data = this.RequestUserAddress.encode(operation).finish();
    var request = this.Request.create();
    request.userAddress = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.Request.encode(request).finish()).toString("hex");
    var result = {signdata:signdata,sid:''};
    return result;
}

/**
 * 获取待维护票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string ownerpublickey    拥有者公钥
 * @return object            
 */
billSign.prototype.signNotes = function(operatorMixkey, ownerpublickey)
{
    var operation = this.RequestNotes.create();
    operation.addr = new Buffers(ownerpublickey,"hex");
    operation.actionId = this.MessageType.values.MsgGetNotes;
    operation.uid = operatorMixkey.slice(32);
    var data = this.RequestNotes.encode(operation).finish();
    var request = this.Request.create();
    request.getNotes = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.Request.encode(request).finish()).toString("hex");
    var result = {signdata:signdata,sid:''};
    return result;
}

/**
 * 增加银行RMB
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string bankPublickey     银行公钥
 * @param  int64  rmb               人民币数量
 * @return object            
 */
billSign.prototype.signIncreaseBankRmb = function(operatorMixkey, bankPublickey, rmb)
{
    var operation = this.RequestIncreaseBankRmb.create();
    operation.bank = new Buffers(bankPublickey,"hex");
    operation.rmb = rmb;
    operation.actionId = this.MessageType.values.MsgIncreaseBankRmb;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestIncreaseBankRmb.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.increaseBankRmb = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 增加银行RMB
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string userPublickey     银行公钥
 * @param  int64 uid                用户id
 * @param  int32 rate               保证金比率
 * @return object            
 */
billSign.prototype.signSetDepositRate = function(operatorMixkey, userPublickey, uid, rate)
{
    var operation = this.RequestSetDepositRate.create();
    operation.userAddress = new Buffers(userPublickey,"hex");
    operation.userUid = uid;
    operation.rate = rate;
    operation.actionId = this.MessageType.values.MsgSetDepositRate;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestSetDepositRate.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.setDepositRate = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 用户提款
 * @param  string operationMixkey    操作用户混合私钥
 * @param  string bankPublickey     用户公钥
 * @param  int64 uid                用户id
 * @param  int64 rmb                人民币数量
 * @return object            
 */
billSign.prototype.signUserWithdraw = function(operationMixkey, bankPublickey, uid, rmb)
{
    var operation = this.RequestUserWithdraw.create();
    operation.bank = new Buffers(bankPublickey,"hex");
    operation.userUid = uid;
    operation.rmb = rmb;
    operation.actionId = this.MessageType.values.MsgUserWithdraw;
    operation.uid = operationMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestUserWithdraw.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.userWithdraw = operation;
    request.sign = nacl.sign(data, operationMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 用户充值
 * @param  string bankMixkey    操作用户混合私钥
 * @param  string userPublickey     用户公钥
 * @param  int64 uid                用户id
 * @param  int64 rmb                人民币数量
 * @return object            
 */
billSign.prototype.signUserDeposit = function(bankMixkey, userPublickey, uid, rmb)
{
    var operation = this.RequestUserDeposit.create();
    operation.user = new Buffers(userPublickey,"hex");
    operation.userUid = uid;
    operation.rmb = rmb;
    operation.actionId = this.MessageType.values.MsgUserDeposit;
    operation.uid = bankMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestUserDeposit.encode(operation).finish();
    var request = this.WriteRequest.create();
    console.log(operation);
    request.userDeposit = operation;
    request.sign = nacl.sign(data, bankMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 创建票据
 * @param  bytes    operatorMixkey 操作者混合私钥
 * @param  string   ownerPublickey 拥有者公钥
 * @param  int64    uid            用户id
 * @param  int32    type           票据类型(1:银票,2:商票)
 * @param  string   billId         票据编号
 * @param  string   drawer         出票人
 * @param  string   taker          收票人
 * @param  string   acceptor       承兑人
 * @param  int64    expiration     到期日
 * @param  int64    nominalValue   票面金额
 * @param  string   billInfo       票据信息
 * @return object
 */
billSign.prototype.signCreateNote = function(operatorMixkey, ownerPublickey, uid,  type, billId, drawer, taker, acceptor, expiration, nominalValue,billInfo)
{
    var operation = this.RequestCreateNote.create();
    operation.type = type;
    operation.id = billId;
    operation.drawer = drawer;
    operation.taker = taker;
    operation.acceptor = acceptor;
    operation.expiration = expiration;
    operation.nominalValue = nominalValue;
    operation.actionId = this.MessageType.values.MsgCreateNote;
    operation.uid = operatorMixkey.slice(32);
    operation.owner = new Buffers(ownerPublickey,"hex");
    operation.createdDate = Date.parse(new Date());
    operation.userUid = uid;
    operation.info = billInfo;
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    console.log(operation);
    var data = this.RequestCreateNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.createNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 挂牌票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string billId            票据编号
 * @param  int64 rmb                挂牌金额
 * @return object            
 */
billSign.prototype.signSellNote = function(operatorMixkey, billId, rmb)
{
    var operation = this.RequestSellNote.create();
    operation.id = billId;
    operation.rmb = rmb;
    operation.actionId = this.MessageType.values.MsgSellNote;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestSellNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.sellNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 操作票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string billId            票据id
 * @param  string operationType     操作类型
 * @return object            
 */
billSign.prototype.signOperateNote = function(operatorMixkey, billId, operationType)
{
    var operation = this.RequestOperateNote.create();
    operation.id = billId;
    operation.actionId = this.getOperationType(operationType);
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestOperateNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.operateNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 资产转移
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  int64  uid               用户id
 * @param  string newPublickKey     新公钥
 * @return object            
 */
billSign.prototype.signTransferAsset = function(operatorMixkey, uid, newPublickKey)
{
    var operation = this.RequestTransferAsset.create();
    operation.userUid = uid;
    operation.newAddr = new Buffers(newPublickKey,"hex");
    operation.actionId = this.MessageType.values.MsgTransferAsset;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestTransferAsset.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.transferAsset = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 删除票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string ownerPublickKey   拥有者公钥
 * @param  string billId            票据编号
 * @return object            
 */
billSign.prototype.signDeleteNote = function(operatorMixkey, ownerPublickKey, billId)
{
    var operation = this.RequestDeleteNote.create();
    operation.id = billId;
    operation.owner = new Buffers(ownerPublickKey,"hex");
    operation.actionId = this.MessageType.values.MsgDeleteNote;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestDeleteNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.deleteNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 贴现票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string ownerPublickKey   拥有者公钥
 * @param  string billId            票据id
 * @param  string discounter        贴现人
 * @param  int32  rate              贴现率
 * @param  int64  date              贴现日期
 * @param  string protocol          贴现协议
 * @param  string toAddr            贴现财务公司
 * @return object            
 */
billSign.prototype.signDiscountNote = function(operatorMixkey, ownerPublickKey, billId, discounter, rate, date, protocol,toAddr)
{
    toAddr = toAddr || '';
    var operation = this.RequestDiscountNote.create();
    operation.id = billId;
    operation.discounter = discounter;
    operation.rate = rate;
    operation.date = date;
    operation.protocol = protocol;
    if(toAddr != ''){
        toAddr = new Buffers(toAddr,"hex");
    }
    operation.toAddr = toAddr;
    operation.owner = new Buffers(ownerPublickKey,"hex");
    operation.actionId = this.MessageType.values.MsgDiscountNote;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestDiscountNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.discountNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 背书票据
 * @param  string operatorMixkey    操作用户混合私钥
 * @param  string ownerPublickKey   拥有者公钥
 * @param  string toPublicKey       背书对象公钥
 * @param  string billId            票据id
 * @return object            
 */
billSign.prototype.signEndorseNote = function(operatorMixkey, ownerPublickKey, toPublicKey, billId)
{
    var operation = this.RequestEndorseNote.create();
    operation.id = billId;
    operation.owner = new Buffers(ownerPublickKey,"hex");
    operation.to = new Buffers(toPublicKey,"hex");
    operation.actionId = this.MessageType.values.MsgEndorseNote;
    operation.uid = operatorMixkey.slice(32);
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestEndorseNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.endorseNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}

/**
 * 修改票据
 * @param  bytes    operatorMixkey 操作者混合私钥
 * @param  string   ownerPublickey 拥有者公钥
 * @param  int64    uid            用户id
 * @param  int32    type           票据类型(1:银票,2:商票)
 * @param  string   billId         票据id
 * @param  string   drawer         出票人
 * @param  string   taker          收票人
 * @param  string   acceptor       承兑人
 * @param  int64    expiration     到期日
 * @param  int64    nominalValue   票面金额
 * @return object
 */
billSign.prototype.signEditNote = function(operatorMixkey, ownerPublickey, uid,  type, billId, drawer, taker, acceptor, expiration, nominalValue)
{
    var operation = this.RequestEditNote.create();
    operation.type = type;
    operation.id = billId;
    operation.drawer = drawer;
    operation.taker = taker;
    operation.acceptor = acceptor;
    operation.expiration = expiration;
    operation.nominalValue = nominalValue;
    operation.actionId = this.MessageType.values.MsgEditNote;
    operation.uid = operatorMixkey.slice(32);
    operation.owner = new Buffers(ownerPublickey,"hex");
    operation.editedDate = Date.parse(new Date());
    operation.userUid = uid;
    var instructionId = this.getInstructionId();
    operation.instructionId = instructionId;
    var data = this.RequestEditNote.encode(operation).finish();
    var request = this.WriteRequest.create();
    request.editNote = operation;
    request.sign = nacl.sign(data, operatorMixkey);
    var signdata = new Buffers(this.WriteRequest.encode(request).finish()).toString("hex");
    var sid = instructionId.toString();
    var result = {signdata:signdata,sid:sid};
    return result;
}
