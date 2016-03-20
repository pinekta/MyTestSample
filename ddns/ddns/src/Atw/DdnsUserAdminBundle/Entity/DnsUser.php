<?php

namespace Atw\DdnsUserAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Atw\DdnsUserAdminBundle\Entity\Support\CreateUpdateDtLifeCycleHelperTrait;
use Atw\DdnsUserAdminBundle\Entity\Support\GetterSetterHelperTrait;
use Atw\DdnsUserAdminBundle\Entity\Support\IsExistInConstantsTrait;

/**
 * DnsUser
 *
 * @UniqueEntity(
 *     fields={"userName"},
 *     errorPath="userName",
 *     message="入力されたユーザ名は既に使用されています。"
 * )
 * @ORM\Table(
 *     name="dns_user",
 *     indexes={
 *         @ORM\Index(name="idx_dns_user_created_at", columns={"created_at"}),
 *         @ORM\Index(name="idx_dns_user_control_no", columns={"control_no"}),
 *         @ORM\Index(name="idx_dns_user_comment", columns={"comment"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Atw\DdnsUserAdminBundle\Repository\DnsUserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class DnsUser
{
    use CreateUpdateDtLifeCycleHelperTrait;
    use GetterSetterHelperTrait;
    use IsExistInConstantsTrait;

    /**
     * MD5
     * htpasswd のオプションは-m
     * Apache server のみ対応
     * Apacheのバージョンによっては動作しない可能性もあります
     */
    const ENCRYPT_TYPE_MD5 = 1;

    /**
     * DES(crypt)
     * htpasswd のオプションは-d
     * 全てのunix servers で対応
     */
    const ENCRYPT_TYPE_DES = 2;

    /**
     * SHA-1
     * htpasswd のオプションは-s
     * Netscape LDIF / Apache server で対応
     */
    const ENCRYPT_TYPE_SHA1 = 3;

    /**
     * プレーンテキスト
     * htpasswd のオプションは-p
     * Windows & TPF server で対応
     */
    const ENCRYPT_TYPE_PLAIN = 9;

    const ENCRYPT_TYPE_LIST = [
        self::ENCRYPT_TYPE_MD5 => 'MD5',
        self::ENCRYPT_TYPE_DES => 'DES',
        self::ENCRYPT_TYPE_SHA1 => 'SHA-1',
        self::ENCRYPT_TYPE_PLAIN => '暗号化しない',
    ];

    const ENCRYPT_HTPASSWD_OPTION_LIST = [
        self::ENCRYPT_TYPE_MD5 => '-m',
        self::ENCRYPT_TYPE_DES => '-d',
        self::ENCRYPT_TYPE_SHA1 => '-s',
        self::ENCRYPT_TYPE_PLAIN => '-p',
    ];

    const HTTP_POSTER_REGIST_URL_FORMAT = 'http://%s:%s@example.co.jp/update/';
    const ADMIN_URL_FORMAT = 'https://%s.example.jp:8888/';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", nullable=false)
     * @Assert\NotBlank(
     *      message = "ユーザ名を入力してください。"
     * )
     * @Assert\Length(
     *       min = "1",
     *       max = "20",
     *       maxMessage = "ユーザ名は {{ limit }} 文字以内で入力してください。"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9-]+$/",
     *     match=true,
     *     message="ユーザ名は半角英数字記号（記号は「-」のみ使用可能）で入力してください。"
     * )
     */
    private $userName;

    /**
     * 編集時パスワードが未入力の場合はパスワードを更新しない仕様のため
     * パスワードの必須チェックはisNotBlankメソッドで行う
     *
     * ※◆注意◆
     * 編集確認時のフォームの入力チェックは$form->isValid()を使用しており、
     * $this->validator->validate($dnsUser)での入力チェックであれば
     * エンティティに元の値を設定すればパスワードの必須チェックをクリアできるのだが、
     * $form->isValid()だと$form内の値でチェックを行っており、
     * そこでの値の設定はできないため別メソッドで必須チェックを行っている
     * また$this->validator->validate($dnsUser)の入力チェックだと
     * FormTypeのTypeをrepeatedに指定した場合、確認用の値との同一チェックが
     * 行われないため、このような形をとっている
     *
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=false)
     * @Assert\Length(
     *       min = "1",
     *       max = "50",
     *       maxMessage = "パスワードは{{ limit }}文字以内で入力してください。"
     * )
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9_-]+$/",
     *     match=true,
     *     message="パスワードは半角英数字記号（記号は「-」「_」のみ使用可能）で入力してください。"
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="control_no", type="string", nullable=true)
     * @Assert\Length(
     *       min = "1",
     *       max = "50",
     *       maxMessage = "管理番号は{{ limit }}文字以内で入力してください。"
     * )
     * @Assert\Regex(
     *     pattern="/^[(\r|\n)]+$/",
     *     match=false,
     *     message="管理番号は改行文字以外で入力してください。"
     * )
     */
    private $controlNo;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", nullable=true)
     * @Assert\Length(
     *       min = "1",
     *       max = "100",
     *       maxMessage = "コメントは{{ limit }}文字以内で入力してください。"
     * )
     * @Assert\Regex(
     *     pattern="/^[(\r|\n)]+$/",
     *     match=false,
     *     message="コメントは改行文字以外で入力してください。"
     * )
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="encrypt_type", type="integer", nullable=false)
     * @Assert\NotBlank(
     *      message = "暗号化方式を選択してください。"
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="暗号化方式の値が不正です。"
     * )
     */
    private $encryptType;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="text", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_at", type="text", nullable=true)
     */
    private $updatedAt;

    /**
     * パスワードの必須チェック
     * 新規の場合のみチェックを行う
     * 更新の場合はパスワードを更新しないのでのでチェックしない
     *
     * @Assert\IsTrue(message = "パスワードを入力してください。")
     */
    public function isNotBlank()
    {
        if (is_null($this->id)) {
            if (is_null($this->password) || $this->password === "") {
                return false;
            }
        }
        return true;
    }

    /**
     * 暗号化方式がDESの場合のパスワードチェック
     * DESの場合９文字以上のパスワードは動作が不安定なので
     * 8文字までの入力とする
     *
     * @Assert\IsTrue(message = "暗号化方式がDESの場合、パスワードは8文字以内で入力してください。")
     */
    public function isLengthOverPasswordIfDes()
    {
        if ($this->encryptType === self::ENCRYPT_TYPE_DES) {
            if (mb_strlen($this->password, 'UTF-8') > 8) {
                return false;
            }
        }
        return true;
    }

    /**
     * 暗号化方式の存在チェック
     * @Assert\IsTrue(message = "暗号化方式の値が不正です。")
     */
    public function isValidEncryptType()
    {
        return $this->isExistInConstants($this->encryptType, self::ENCRYPT_TYPE_LIST);
    }

    /**
     * パスワードが空の場合引数の値(修正前の値)でリカバリする
     * @param string $prev
     * @return string
     */
    public function recoveryPasswordIfNull($prev = "")
    {
        if ($prev === "") {
            return;
        }
        if (is_null($this->password()) || $this->password() === "") {
            $this->password = $prev;
        }
    }

    /**
     * ユーザ名とパスワードを元にHTTPPoster登録URLを生成する
     * @param string $userName
     * @param string $password
     * @return string
     */
    public function generateHTTPPosterRegistURL($userName, $password)
    {
        return sprintf(self::HTTP_POSTER_REGIST_URL_FORMAT, $userName, $password);
    }

    /**
     * ユーザ名を元に管理画面URLを生成する
     * @param string $userName
     * @return string
     */
    public function generateAdminURL($userName)
    {
        return sprintf(self::ADMIN_URL_FORMAT, $userName);
    }
}
