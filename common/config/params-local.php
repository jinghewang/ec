<?php
return [
    'company' => [
        'code' => 1,
        'name' => '北京智明信通科技有限公司',
        'enname' => 'vive',
        'powered' => '北京汇来科技有限公司',
        'copyright' => '酸茄子'
    ],
    'sms-vive' => [
        'api' => 'http://inolink.com/ws/LinkWS.asmx?wsdl',
        'username' => 'TCLKJ04265',
        'password' => '15210061902@',
        'charset' => 'utf8'
    ],
    'sms' => [
        'api' => 'http://inolink.com/ws/LinkWS.asmx?wsdl',
        'username' => 'TCLKJ03468',//'TCLKJ04265',
        'password' => '18500307001@w',//'15210061902@',
        'charset' => 'utf8',
        'template' => '{$name}你好，电子合同(合同号：{$cno})已经备案，请登录{$url}进行签字确认,授权号：{$code}，{$company}',
        'template_code' => '电子合同(合同号：{$cno}),授权号：{$code}，{$company}',
        'template_cancel'=>'{$name},你在{$company}签订的编号为{$cno}的合同，已经取消成功'
    ]
];
