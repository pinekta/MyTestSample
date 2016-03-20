<?php

namespace Atw\DdnsUserAdminBundle\Service\Htpasswd;

use Doctrine\ORM\EntityManagerInterface;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Exception\HtpasswdException;

/**
 * Class HtpasswdManager
 */
class HtpasswdManager implements HtpasswdManagerInterface
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

    /** @var string */
    private $htpasswdFile;

    /** @var string */
    private $tmpHtpasswdFile;

    /**
     * @param EntityManagerInterface $em
     * @param string $htpasswdFile
     * @param string $tmpHtpasswdFile
     */
    public function __construct(EntityManagerInterface $em, $htpasswdFile, $tmpHtpasswdFile)
    {
        $this->em = $em;
        $this->htpasswdFile = $htpasswdFile;
        $this->tmpHtpasswdFile = $tmpHtpasswdFile;
    }

    /**
     * htpasswdファイルを生成する
     * 本メソッドがワークファイルの削除・生成・置き換えのメソッドをコールする
     * @throws HtpasswdException
     */
    public function tryGenerateHtpasswd()
    {
        $this->deleteTmpHtpasswd();
        $this->generateTmpHtpasswd();
        $this->replaceHtpasswd();
    }

    /**
     * htpasswdワークファイルを削除する
     * @throws HtpasswdException
     */
    private function deleteTmpHtpasswd()
    {
        if (file_exists($this->tmpHtpasswdFile)) {
            if (!unlink($this->tmpHtpasswdFile)) {
                throw new HtpasswdException('htpasswdワークファイルの削除に失敗しました。');
            }
        }
    }

    /**
     * dns_userテーブルよりhtpasswdワークファイルを生成する
     */
    private function generateTmpHtpasswd()
    {
        $entities = $this->em->getRepository(DnsUser::class)->findAll();
        $isFirst = true;
        foreach ($entities as $entity) {
            $this->execHtpasswdCommand($entity, $isFirst);
            $isFirst = false;
        }
    }

    /**
     * 作成したhtpasswdワークファイルでhtpasswdファイルを置き換える
     * @throws HtpasswdException
     */
    private function replaceHtpasswd()
    {
        if (file_exists($this->tmpHtpasswdFile)) {
            if (!rename($this->tmpHtpasswdFile, $this->htpasswdFile)) {
                throw new HtpasswdException('htpasswdワークファイルの移動に失敗しました。');
            }
        }
    }

    /**
     * 暗号化方式をhtpasswdコマンド用のオプション形式に変換する
     * @param integer $encryptType
     * @return string $option ("-m", "-d", "-s", "-p")
     * @throws HtpasswdException
     */
    private function convertEncryptTypeToOption($encryptType)
    {
        $option = DnsUser::ENCRYPT_HTPASSWD_OPTION_LIST[$encryptType];
        if (is_null($option) || $option === "") {
            throw new HtpasswdException("htpasswdワークファイルの作成に失敗しました。不適切なencryptType({$encryptType})が指定されました。");
        }
        return $option;
    }

    /**
     * htpasswdコマンドを実行する
     * @throws HtpasswdException
     */
    private function execHtpasswdCommand(DnsUser $entity, $isFirst)
    {
        if (!$entity) {
            throw new HtpasswdException("DnsUserエンティティを指定してください。");
        }

        $fileOption = $isFirst ? "-c" : "";
        $username = escapeshellarg($entity->getUserName());
        $password = escapeshellarg($entity->getPassword());
        $encryptTypeOption = $this->convertEncryptTypeToOption($entity->getEncryptType());

        $command = "htpasswd -b {$fileOption} {$encryptTypeOption} {$this->tmpHtpasswdFile} {$username} {$password};";
        $output = [];
        $str = exec($command, $output, $ret);
        if ($ret !== 0) {
            throw new HtpasswdException("htpasswdコマンドに失敗しました。(username: {$username}, password: {$password}, encryptType: {$entity->getEncryptType()})");
        }
    }
}
