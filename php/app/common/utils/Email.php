<?php

declare(strict_types=1);

namespace app\common\utils;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public function send($data)
    {
        $to = $data['to'];
        if (!is_array($to)) {
            $to = [$to];
        }
        $toC = $data['toC'];
        if (!is_array($toC)) {
            $toC = [$toC];
        }
        $title = $data['title'];
        $attachment = $data['attachment'] ?? []; //['value'=>'','label'=>''];
        if (!is_array($attachment)) {
            $attachment = [$attachment];
        }
        $body = $data['body'];

        $Username = 'administartor@yqt.life';
        $Password = 'administartor';
        $mail = new PHPMailer();
        $mail->isSMTP();  //使用smtp鉴权方式发送邮件
        $mail->CharSet = 'utf8';   //设置编码
        $mail->Host = 'smtp.exmail.qq.com';  //qq邮箱smtp邮箱
        $mail->SMTPAuth = true;    //是否需要认证身份
        $mail->Username = $Username;  //发送方邮箱
        $mail->Password = $Password;    //发送方smtp密码 
        $mail->SMTPSecure = 'ssl';    //使用的协议
        $mail->Port = 465;   //qq邮箱接收的端口号
        $mail->setFrom($Username, $Username);  //定义邮件及标题（不同邮件标题显示不一致）
        foreach ($to as $v) {
            $mail->addAddress($v);  //要发送的地址和设置地址的昵称
        }
        foreach ($toC as $v) {
            $mail->addCC($v);  //添加抄送人
        }
        foreach ($attachment as $v) {
            if (!is_array($v)) {
                $v = [
                    'value' => $v,
                    'label' => $v
                ];
            }
            $value = $v['value'];
            $label = $v['label'] ?? $v['value'];
            $mail->AddAttachment($value, $label);
        }
        $mail->Subject = $title;  //添加该邮件的主题
        $mail->Body = $body; //该邮件内容
        if (!$mail->send()) {
            return $mail->ErrorInfo;
        } else {
            return 0;
        }
    }
}
