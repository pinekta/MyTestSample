/**
 * requires
 */
var gulp = require("gulp");
var plumber = require('gulp-plumber');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var csscon = require('gulp-concat-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var addsrc = require('gulp-add-src');
var srcmap = require('gulp-sourcemaps');
//var imgmin = require('gulp-imagemin');
//var pngqnt = require('imagemin-pngquant');

/**
 * css,jsの格納先ディレクトリ
 * 以下のシンボリックリンクを経由したパスだと動作しなかったので
 * src以下の正式なパスを指定している
 * ※動作しないパス：var src = 'web/bundles/atwddnsadmin';
 */
var src = 'src/Atw/DdnsUserAdminBundle/Resources/public';

/**
 * cssの圧縮
 */
gulp.task('cssmin', function () {
    gulp.src([src + '/css/*.css', '!' + src + '/css/*.min.css'])
        .pipe(plumber())
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(src + '/css/'));
});

/**
 * cssを結合し圧縮
 */
gulp.task('csscon', ['cssmin'], function () {
    gulp.src(src + '/css/*.min.css')
        .pipe(plumber())
        .pipe(csscon('bundle.css'))
        .pipe(cssmin())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(src + '/css/'));
});

/**
 * jsの圧縮
 */
gulp.task('uglify', function () {
    gulp.src([src + '/js/**/*.js', '!' + src + '/js/**/*.min.js'])
        .pipe(plumber())
        .pipe(uglify({preserveComments: 'some'}))    // ライセンス表域は圧縮しない
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(src + '/js/'));
});

/**
 * jsの結合
 * ※結合に順序があることに注意
 * 汎用的なスクリプトのみ結合する(js/libsの一部とjs/commonのすべて)
 */
gulp.task('concat', ['uglify'], function () {
    gulp.src(src + '/js/libs/jquery-1.10.2.min.js')
        .pipe(addsrc.append(src + '/js/libs/bootstrap.min.js'))
        .pipe(addsrc.append(src + '/js/libs/bootstrap-notify.min.js'))
        .pipe(addsrc.append(src + '/js/libs/light-bootstrap-dashboard.min.js'))
        .pipe(addsrc.append(src + '/js/common/*.min.js'))
        .pipe(plumber())
        .pipe(srcmap.init())
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(srcmap.write('source-maps'))
        .pipe(gulp.dest(src + '/js/'));
});

/**
 * 画像の圧縮
 */
//gulp.task('optimizeimage', function () {
//    gulp.src(src + '/js/img/**/*.+(jpg|jpeg|png|gif|svg)')
//        .pipe(plumber())
//        .pipe(imagemin({use: [pngquant({quality: '65-80', speed: 1})]}))
//        .pipe(rename({suffix: '.min'}))
//        .pipe(gulp.dest(src + '/img/'));
//});

/**
 * 変更の監視
 *
 * ※注意
 * min.jsやmin.cssの変更は監視しません
 * これらを監視対象として含めてしまうと
 * タスクが複数回走ってファイルが破損する可能性があるため
 */
gulp.task('watch', function () {
    //var watcher = gulp.watch([src + '/**/*'], ['csscon', 'concat', 'optimizeimage']);
    var watcherCss = gulp.watch([src + '/css/**/*.css', '!' + src + '/css/**/*.min.css'], ['csscon']);
    watcherCss.on('change', function(e) {
        console.log('file: ' + e.path + ', type: ' + e.type);
    });
    var watcherJs = gulp.watch([src + '/js/**/*.js', '!' + src + '/js/**/*.min.js'], ['concat']);
    watcherJs.on('change', function(e) {
        console.log('file: ' + e.path + ', type: ' + e.type);
    });
});

/**
 * defaultのタスク
 */
//gulp.task('default', ['csscon', 'concat', 'optimizeimage']);
gulp.task('default', ['csscon', 'concat']);
