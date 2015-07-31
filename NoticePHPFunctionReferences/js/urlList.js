var urlList = [
"function.abs",
"function.addslashes",
"function.array",
"function.array-change-key-case",
"function.array-chunk",
"function.array-column",
"function.array-combine",
"function.array-count-values",
"function.array-diff",
"function.array-diff-assoc",
"function.array-diff-key",
"function.array-diff-uassoc",
"function.array-diff-ukey",
"function.array-fill",
"function.array-fill-keys",
"function.array-filter",
"function.array-flip",
"function.array-intersect",
"function.array-intersect-assoc",
"function.array-intersect-key",
"function.array-intersect-uassoc",
"function.array-intersect-ukey",
"function.array-keys",
"function.array-key-exists",
"function.array-map",
"function.array-merge",
"function.array-merge-recursive",
"function.array-multisort",
"function.array-pad",
"function.array-pop",
"function.array-product",
"function.array-push",
"function.array-rand",
"function.array-reduce",
"function.array-replace",
"function.array-replace-recursive",
"function.array-reverse",
"function.array-search",
"function.array-shift",
"function.array-slice",
"function.array-splice",
"function.array-sum",
"function.array-udiff",
"function.array-udiff-assoc",
"function.array-udiff-uassoc",
"function.array-uintersect",
"function.array-uintersect-assoc",
"function.array-uintersect-uassoc",
"function.array-unique",
"function.array-unshift",
"function.array-values",
"function.array-walk",
"function.array-walk-recursive",
"function.arsort",
"function.asort",
"function.assert",
"function.assert-options",
"function.base64-decode",
"function.base64-encode",
"function.basename",
"function.base-convert",
"function.boolval",
"function.bson-decode",
"function.bson-encode",
"function.ceil",
"function.chdir",
"function.checkdate",
"function.chgrp",
"function.chmod",
"function.chown",
"function.chr",
"function.chroot",
"function.chunk-split",
"function.class-exists",
"function.class-implements",
"function.class-parents",
"function.class-uses",
"function.clearstatcache",
"function.closedir",
"function.closelog",
"function.compact",
"function.constant",
"function.copy",
"function.count",
"function.crypt",
"function.ctype-alnum",
"function.ctype-alpha",
"function.ctype-cntrl",
"function.ctype-digit",
"function.ctype-graph",
"function.ctype-lower",
"function.ctype-print",
"function.ctype-punct",
"function.ctype-space",
"function.ctype-upper",
"function.ctype-xdigit",
//"curl コンテキストオプション",
"function.current",
"function.date",
"dateinterval.createfromdatestring",
"dateinterval.format",
"dateinterval.construct",
"dateperiod.construct",
"datetime.add",
"datetime.createfromformat",
"datetime.diff",
"datetime.format",
"datetime.getlasterrors",
"datetime.getoffset",
"datetime.gettimestamp",
"datetime.gettimezone",
"datetime.modify",
"datetime.setdate",
"datetime.setisodate",
"datetime.settime",
"datetime.settimestamp",
"datetime.settimezone",
"datetime.sub",
"datetime.construct",
"datetime.set-state",
"datetime.wakeup",
"datetimezone.getlocation",
"datetimezone.getname",
"datetimezone.getoffset",
"datetimezone.gettransitions",
"datetimezone.listabbreviations",
"datetimezone.listidentifiers",
"datetimezone.construct",
"function.define",
"function.defined",
"function.die",
"function.dir",
"function.dirname",
"function.each",
"function.echo",
"function.empty",
"function.error-log",
"function.error-reporting",
"function.eval",
"exception.getcode",
"exception.getfile",
"exception.getline",
"exception.getmessage",
"exception.getprevious",
"exception.gettrace",
"exception.gettraceasstring",
"exception.clone",
"exception.construct",
"exception.tostring",
"function.exec",
"function.exit",
//"expect://",
"function.explode",
"function.extract",
"function.fclose",
"function.file",
//"file://",
"function.filesize",
"function.filetype",
"function.file-exists",
"function.file-get-contents",
"function.file-put-contents",
"function.flush",
"function.fopen",
"function.fprintf",
"function.fputcsv",
"function.fputs",
"function.fread",
"function.function-exists",
"function.func-get-arg",
"function.func-get-args",
"function.func-num-args",
"function.fwrite",
"function.getcwd",
"function.getdate",
"function.getenv",
"function.getimagesize",
"function.getimagesizefromstring",
"function.getlastmod",
"function.getmxrr",
"function.gettype",
"function.get-class",
"function.get-class-methods",
"function.get-class-vars",
"function.get-headers",
"function.get-included-files",
"function.get-include-path",
"function.hash",
"function.hash-algos",
"function.hash-copy",
"function.hash-equals",
"function.hash-file",
"function.hash-final",
"function.hash-hmac",
"function.hash-hmac-file",
"function.hash-init",
"function.hash-pbkdf2",
"function.hash-update",
"function.hash-update-file",
"function.hash-update-stream",
"function.header",
"function.headers-list",
"function.headers-sent",
"function.header-register-callback",
"function.header-remove",
"function.hex2bin",
"function.hexdec",
"function.htmlentities",
"function.htmlspecialchars",
"function.htmlspecialchars-decode",
"function.html-entity-decode",
"function.implode",
"function.ini-get",
"function.ini-get-all",
"function.ini-restore",
"function.ini-set",
"function.interface-exists",
"function.intval",
"function.in-array",
"function.ip2long",
"function.isset",
"function.is-a",
"function.is-array",
"function.is-bool",
"function.is-callable",
"function.is-dir",
"function.is-double",
"function.is-executable",
"function.is-file",
"function.is-finite",
"function.is-float",
"function.is-infinite",
"function.is-int",
"function.is-integer",
"function.is-link",
"function.is-long",
"function.is-nan",
"function.is-null",
"function.is-numeric",
"function.is-object",
"function.is-readable",
"function.is-real",
"function.is-resource",
"function.is-scalar",
"function.is-soap-fault",
"function.is-string",
"function.is-subclass-of",
"function.is-tainted",
"function.is-uploaded-file",
"function.is-writable",
"function.json-decode",
"function.json-encode",
"function.key",
"function.key-exists",
"function.krsort",
"function.ksort",
"function.lcfirst",
"function.lchgrp",
"function.lchown",
"function.link",
"function.list",
"function.ltrim",
"function.mail",
"function.max",
"function.mb-check-encoding",
"function.mb-convert-encoding",
"function.mb-convert-kana",
"function.mb-send-mail",
"function.mb-split",
"function.mb-strcut",
"function.mb-stripos",
"function.mb-stristr",
"function.mb-strlen",
"function.mb-strpos",
"function.mb-strrchr",
"function.mb-strrichr",
"function.mb-strripos",
"function.mb-strrpos",
"function.mb-strstr",
"function.mb-strtolower",
"function.mb-strtoupper",
"function.mb-substr",
"function.mb-substr-count",
"function.md5",
"function.md5-file",
"function.method-exists",
"function.microtime",
"function.min",
"function.mkdir",
"function.mktime",
"function.move-uploaded-file",
"function.natcasesort",
"function.natsort",
"function.next",
"function.nl2br",
"function.ob-clean",
"function.ob-flush",
"function.ob-start",
"function.opendir",
"function.openlog",
"function.parse-ini-file",
"function.parse-ini-string",
"function.parse-str",
"function.parse-url",
"function.passthru",
"function.password-get-info",
"function.password-hash",
"function.password-needs-rehash",
"function.password-verify",
//"phar コンテキストオプション",
//"php://",
"function.phpinfo",
"function.phpversion",
"function.preg-filter",
"function.preg-grep",
"function.preg-last-error",
"function.preg-match",
"function.preg-match-all",
"function.preg-quote",
"function.preg-replace",
"function.preg-replace-callback",
"function.preg-split",
"function.prev",
"function.print",
"function.printf",
"function.print-r",
"function.property-exists",
"function.quotemeta",
"function.rand",
"function.range",
"function.readdir",
"function.readfile",
"function.readgzfile",
"function.readline",
"function.readlink",
"function.realpath",
"function.rename",
"function.reset",
"function.rmdir",
"function.round",
"function.rsort",
"function.rtrim",
"serializable.serialize",
"serializable.unserialize",
"function.serialize",
"function.setcookie",
"function.setlocale",
"function.shuffle",
"function.signeurlpaiement",
"function.sleep",
"function.sort",
"function.split",
"function.spliti",
"function.sprintf",
"function.strcasecmp",
"function.strchr",
"function.strcmp",
"function.strcoll",
"function.strcspn",
"function.strftime",
"function.stripcslashes",
"function.stripos",
"function.stripslashes",
"function.strip-tags",
"function.stristr",
"function.strlen",
"function.strnatcasecmp",
"function.strnatcmp",
"function.strncasecmp",
"function.strncmp",
"function.strpbrk",
"function.strpos",
"function.strptime",
"function.strrchr",
"function.strrev",
"function.strripos",
"function.strrpos",
"function.strspn",
"function.strstr",
"function.strtok",
"function.strtolower",
"function.strtotime",
"function.strtoupper",
"function.strtr",
"function.strval",
"function.substr",
"function.substr-compare",
"function.substr-count",
"function.substr-replace",
"function.symlink",
"function.syslog",
"function.system",
"function.touch",
"function.trait-exists",
"function.trigger-error",
"function.trim",
"function.uasort",
"function.ucfirst",
"function.ucwords",
"function.uksort",
"function.umask",
"function.uniqid",
"function.unlink",
"function.unserialize",
"function.unset",
"function.untaint",
"function.urldecode",
"function.urlencode",
"function.usort",
"function.utf8-decode",
"function.utf8-encode",
"function.var-dump",
"function.var-export",
"function.version-compare",
"function.vfprintf",
"function.virtual",
"function.vprintf",
"function.vsprintf"
];
