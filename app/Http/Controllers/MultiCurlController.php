<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;


class MultiCurlController extends Controller
{
    public function index()
    {
        // $str = "北京:101010100朝阳:101010300顺义:101010400怀柔:101010500通州:101010600昌平:101010700延庆:101010800丰台:101010900石景山:101011000大兴:101011100房山:101011200密云:101011300门头沟:101011400平谷:101011500八达岭:101011600佛爷顶:101011700汤河口:101011800密云上甸子:101011900斋堂:101012000霞云岭:101012100北京城区:101012200海淀:101010200天津:101030100宝坻:101030300东丽:101030400西青:101030500北辰:101030600蓟县:101031400汉沽:101030800静海:101030900津南:101031000塘沽:101031100大港:101031200武清:101030200宁河:101030700上海:101020100宝山:101020300嘉定:101020500南汇:101020600浦东:101021300青浦:101020800松江:101020900奉贤:101021000崇明:101021100徐家汇:101021200闵行:101020200金山:101020700石家庄:101090101张家口:101090301承德:101090402唐山:101090501秦皇岛:101091101沧州:101090701衡水:101090801邢台:101090901邯郸:101091001保定:101090201廊坊:101090601郑州:101180101新乡:101180301许昌:101180401平顶山:101180501信阳:101180601南阳:101180701开封:101180801洛阳:101180901商丘:101181001焦作:101181101鹤壁:101181201濮阳:101181301周口:101181401漯河:101181501驻马店:101181601三门峡:101181701济源:101181801安阳:101180201合肥:101220101芜湖:101220301淮南:101220401马鞍山:101220501安庆:101220601宿州:101220701阜阳:101220801亳州:101220901黄山:101221001滁州:101221101淮北:101221201铜陵:101221301宣城:101221401六安:101221501巢湖:101221601池州:101221701蚌埠:101220201杭州:101210101舟山:101211101湖州:101210201嘉兴:101210301金华:101210901绍兴:101210501台州:101210601温州:101210701丽水:101210801衢州:101211001宁波:101210401重庆:101040100合川:101040300南川:101040400江津:101040500万盛:101040600渝北:101040700北碚:101040800巴南:101040900长寿:101041000黔江:101041100万州天城:101041200万州龙宝:101041300涪陵:101041400开县:101041500城口:101041600云阳:101041700巫溪:101041800奉节:101041900巫山:101042000潼南:101042100垫江:101042200梁平:101042300忠县:101042400石柱:101042500大足:101042600荣昌:101042700铜梁:101042800璧山:101042900丰都:101043000武隆:101043100彭水:101043200綦江:101043300酉阳:101043400秀山:101043600沙坪坝:101043700永川:101040200福州:101230101泉州:101230501漳州:101230601龙岩:101230701晋江:101230509南平:101230901厦门:101230201宁德:101230301莆田:101230401三明:101230801兰州:101160101平凉:101160301庆阳:101160401武威:101160501金昌:101160601嘉峪关:101161401酒泉:101160801天水:101160901武都:101161001临夏:101161101合作:101161201白银:101161301定西:101160201张掖:101160701广州:101280101惠州:101280301梅州:101280401汕头:101280501深圳:101280601珠海:101280701佛山:101280800肇庆:101280901湛江:101281001江门:101281101河源:101281201清远:101281301云浮:101281401潮州:101281501东莞:101281601中山:101281701阳江:101281801揭阳:101281901茂名:101282001汕尾:101282101韶关:101280201南宁:101300101柳州:101300301来宾:101300401桂林:101300501梧州:101300601防城港:101301401贵港:101300801玉林:101300901百色:101301001钦州:101301101河池:101301201北海:101301301崇左:101300201贺州:101300701贵阳:101260101安顺:101260301都匀:101260401兴义:101260906铜仁:101260601毕节:101260701六盘水:101260801遵义:101260201凯里:101260501昆明:101290101红河:101290301文山:101290601玉溪:101290701楚雄:101290801普洱:101290901昭通:101291001临沧:101291101怒江:101291201香格里拉:101291301丽江:101291401德宏:101291501景洪:101291601大理:101290201曲靖:101290401保山:101290501呼和浩特:101080101乌海:101080301集宁:101080401通辽:101080501阿拉善左旗:101081201鄂尔多斯:101080701临河:101080801锡林浩特:101080901呼伦贝尔:101081000乌兰浩特:101081101包头:101080201赤峰:101080601南昌:101240101上饶:101240301抚州:101240401宜春:101240501鹰潭:101241101赣州:101240701景德镇:101240801萍乡:101240901新余:101241001九江:101240201吉安:101240601武汉:101200101黄冈:101200501荆州:101200801宜昌:101200901恩施:101201001十堰:101201101神农架:101201201随州:101201301荆门:101201401天门:101201501仙桃:101201601潜江:101201701襄樊:101200201鄂州:101200301孝感:101200401黄石:101200601咸宁:101200701成都:101270101自贡:101270301绵阳:101270401南充:101270501达州:101270601遂宁:101270701广安:101270801巴中:101270901泸州:101271001宜宾:101271101内江:101271201资阳:101271301乐山:101271401眉山:101271501凉山:101271601雅安:101271701甘孜:101271801阿坝:101271901德阳:101272001广元:101272101攀枝花:101270201银川:101170101中卫:101170501固原:101170401石嘴山:101170201吴忠:101170301西宁:101150101黄南:101150301海北:101150801果洛:101150501玉树:101150601海西:101150701海东:101150201海南:101150401济南:101120101潍坊:101120601临沂:101120901菏泽:101121001滨州:101121101东营:101121201威海:101121301枣庄:101121401日照:101121501莱芜:101121601聊城:101121701青岛:101120201淄博:101120301德州:101120401烟台:101120501济宁:101120701泰安:101120801西安:101110101延安:101110300榆林:101110401铜川:101111001商洛:101110601安康:101110701汉中:101110801宝鸡:101110901咸阳:101110200渭南:101110501太原:101100101临汾:101100701运城:101100801朔州:101100901忻州:101101001长治:101100501大同:101100201阳泉:101100301晋中:101100401晋城:101100601吕梁:101101100乌鲁木齐:101130101石河子:101130301昌吉:101130401吐鲁番:101130501库尔勒:101130601阿拉尔:101130701阿克苏:101130801喀什:101130901伊宁:101131001塔城:101131101哈密:101131201和田:101131301阿勒泰:101131401阿图什:101131501博乐:101131601克拉玛依:101130201拉萨:101140101山南:101140301阿里:101140701昌都:101140501那曲:101140601日喀则:101140201林芝:101140401台北县:101340101高雄:101340201台中:101340401海口:101310101三亚:101310201东方:101310202临高:101310203澄迈:101310204儋州:101310205昌江:101310206白沙:101310207琼中:101310208定安:101310209屯昌:101310210琼海:101310211文昌:101310212保亭:101310214万宁:101310215陵水:101310216西沙:101310217南沙岛:101310220乐东:101310221五指山:101310222琼山:101310102长沙:101250101株洲:101250301衡阳:101250401郴州:101250501常德:101250601益阳:101250700娄底:101250801邵阳:101250901岳阳:101251001张家界:101251101怀化:101251201黔阳:101251301永州:101251401吉首:101251501湘潭:101250201南京:101190101镇江:101190301苏州:101190401南通:101190501扬州:101190601宿迁:101191301徐州:101190801淮安:101190901连云港:101191001常州:101191101泰州:101191201无锡:101190201盐城:101190701哈尔滨:101050101牡丹江:101050301佳木斯:101050401绥化:101050501黑河:101050601双鸭山:101051301伊春:101050801大庆:101050901七台河:101051002鸡西:101051101鹤岗:101051201齐齐哈尔:101050201大兴安岭:101050701长春:101060101延吉:101060301四平:101060401白山:101060901白城:101060601辽源:101060701松原:101060801吉林:101060201通化:101060501沈阳:101070101鞍山:101070301抚顺:101070401本溪:101070501丹东:101070601葫芦岛:101071401营口:101070801阜新:101070901辽阳:101071001铁岭:101071101朝阳:101071201盘锦:101071301大连:101070201锦州:101070701";
        // $pattern = "/\d+/";
        // $citystr = preg_match_all($pattern, $str, $keyswords);
        // $keyswords = reset($keyswords);
        // $pattern2 = "/[\x{4e00}-\x{9fa5}]+/u"; //不加/u报错"preg_match_all(): Compilation failed: character value in \x{} or \o{} is too large at offset 8“
        // $citystr2 = preg_match_all($pattern2, $str, $keyswords2);
        // $keyswords2 = reset($keyswords2);
        // foreach ($keyswords as $k => $one) {
        //     echo "'" . $one . "'" . ',//' . $keyswords2[$k] . PHP_EOL;
        // }
        //上面的是网上复制的 稍微处理一下 可以很快的生成下面的数组的内容


        //这里开始才是正题    
        $city_code_arr = [
            '101010100', //北京
            '101010300', //朝阳
            '101010400', //顺义
            '101010500', //怀柔
            '101010600', //通州
            '101010700', //昌平
            '101010800', //延庆
            '101010900', //丰台
            '101011000', //石景山
            '101011100', //大兴
            '101011200', //房山
            '101011300', //密云
            '101011400', //门头沟
            '101011500', //平谷
            '101011600', //八达岭
            '101011700', //佛爷顶
            '101011800', //汤河口
            '101011900', //密云上甸子
            '101012000', //斋堂
            '101012100', //霞云岭
            //'101012200', //北京城区
            '101010200', //海淀
            '101030100', //天津
            '101030300', //宝坻
            '101030400', //东丽
            '101030500', //西青
            '101030600', //北辰
            '101031400', //蓟县
            '101030800', //汉沽
            '101030900', //静海
            '101031000', //津南
            '101031100', //塘沽
            '101031200', //大港
            '101030200', //武清
            '101030700', //宁河
            '101020100', //上海
            '101020300', //宝山
            '101020500', //嘉定
            '101020600', //南汇
            '101021300', //浦东
            '101020800', //青浦
            '101020900', //松江
            '101021000', //奉贤
            '101021100', //崇明
            '101021200', //徐家汇
            '101020200', //闵行
            '101020700', //金山
            '101090101', //石家庄
            '101090301', //张家口
            '101090402', //承德
            '101090501', //唐山
            '101091101', //秦皇岛
            '101090701', //沧州
            '101090801', //衡水
            '101090901', //邢台
            '101091001', //邯郸
            '101090201', //保定
            '101090601', //廊坊
            '101180101', //郑州
            '101180301', //新乡
            '101180401', //许昌
            '101180501', //平顶山
            '101180601', //信阳
            '101180701', //南阳
            '101180801', //开封
            '101180901', //洛阳
            '101181001', //商丘
            '101181101', //焦作
            '101181201', //鹤壁
            '101181301', //濮阳
            '101181401', //周口
            '101181501', //漯河
            '101181601', //驻马店
            '101181701', //三门峡
            '101181801', //济源
            '101180201', //安阳
            '101220101', //合肥
            '101220301', //芜湖
            '101220401', //淮南
            '101220501', //马鞍山
            '101220601', //安庆
            '101220701', //宿州
            '101220801', //阜阳
            '101220901', //亳州
            '101221001', //黄山
            '101221101', //滁州
            '101221201', //淮北
            '101221301', //铜陵
            '101221401', //宣城
            '101221501', //六安
            '101221601', //巢湖
            '101221701', //池州
            '101220201', //蚌埠
            '101210101', //杭州
            '101211101', //舟山
            '101210201', //湖州
            '101210301', //嘉兴
            '101210901', //金华
            '101210501', //绍兴
            '101210601', //台州
            '101210701', //温州
            '101210801', //丽水
            '101211001', //衢州
            '101210401', //宁波
            '101040100', //重庆
            '101040300', //合川
            '101040400', //南川
            '101040500', //江津
            '101040600', //万盛
            '101040700', //渝北
            '101040800', //北碚
            '101040900', //巴南
            '101041000', //长寿
            '101041100', //黔江
            '101041200', //万州天城
            '101041300', //万州龙宝
            '101041400', //涪陵
            '101041500', //开县
            '101041600', //城口
            '101041700', //云阳
            '101041800', //巫溪
            '101041900', //奉节
            '101042000', //巫山
            '101042100', //潼南
            '101042200', //垫江
            '101042300', //梁平
            '101042400', //忠县
            '101042500', //石柱
            '101042600', //大足
            '101042700', //荣昌
            '101042800', //铜梁
            '101042900', //璧山
            '101043000', //丰都
            '101043100', //武隆
            '101043200', //彭水
            '101043300', //綦江
            '101043400', //酉阳
            '101043600', //秀山
            //'101043700', //沙坪坝
            '101040200', //永川
            '101230101', //福州
            '101230501', //泉州
            '101230601', //漳州
            '101230701', //龙岩
            '101230509', //晋江
            '101230901', //南平
            '101230201', //厦门
            '101230301', //宁德
            '101230401', //莆田
            '101230801', //三明
            '101160101', //兰州
            '101160301', //平凉
            '101160401', //庆阳
            '101160501', //武威
            '101160601', //金昌
            '101161401', //嘉峪关
            '101160801', //酒泉
            '101160901', //天水
            '101161001', //武都
            '101161101', //临夏
            '101161201', //合作
            '101161301', //白银
            '101160201', //定西
            '101160701', //张掖
            '101280101', //广州
            '101280301', //惠州
            '101280401', //梅州
            '101280501', //汕头
            '101280601', //深圳
            '101280701', //珠海
            '101280800', //佛山
            '101280901', //肇庆
            '101281001', //湛江
            '101281101', //江门
            '101281201', //河源
            '101281301', //清远
            '101281401', //云浮
            '101281501', //潮州
            '101281601', //东莞
            '101281701', //中山
            '101281801', //阳江
            '101281901', //揭阳
            '101282001', //茂名
            '101282101', //汕尾
            '101280201', //韶关
            '101300101', //南宁
            '101300301', //柳州
            '101300401', //来宾
            '101300501', //桂林
            '101300601', //梧州
            '101301401', //防城港
            '101300801', //贵港
            '101300901', //玉林
            '101301001', //百色
            '101301101', //钦州
            '101301201', //河池
            '101301301', //北海
            '101300201', //崇左
            '101300701', //贺州
            '101260101', //贵阳
            '101260301', //安顺
            '101260401', //都匀
            '101260906', //兴义
            '101260601', //铜仁
            '101260701', //毕节
            '101260801', //六盘水
            '101260201', //遵义
            '101260501', //凯里
            '101290101', //昆明
            '101290301', //红河
            '101290601', //文山
            '101290701', //玉溪
            '101290801', //楚雄
            '101290901', //普洱
            '101291001', //昭通
            '101291101', //临沧
            '101291201', //怒江
            '101291301', //香格里拉
            '101291401', //丽江
            '101291501', //德宏
            '101291601', //景洪
            '101290201', //大理
            '101290401', //曲靖
            '101290501', //保山
            '101080101', //呼和浩特
            '101080301', //乌海
            '101080401', //集宁
            '101080501', //通辽
            '101081201', //阿拉善左旗
            '101080701', //鄂尔多斯
            '101080801', //临河
            '101080901', //锡林浩特
            '101081000', //呼伦贝尔
            '101081101', //乌兰浩特
            '101080201', //包头
            '101080601', //赤峰
            '101240101', //南昌
            '101240301', //上饶
            '101240401', //抚州
            '101240501', //宜春
            '101241101', //鹰潭
            '101240701', //赣州
            '101240801', //景德镇
            '101240901', //萍乡
            '101241001', //新余
            '101240201', //九江
            '101240601', //吉安
            '101200101', //武汉
            '101200501', //黄冈
            '101200801', //荆州
            '101200901', //宜昌
            '101201001', //恩施
            '101201101', //十堰
            '101201201', //神农架
            '101201301', //随州
            '101201401', //荆门
            '101201501', //天门
            '101201601', //仙桃
            '101201701', //潜江
            '101200201', //襄樊
            '101200301', //鄂州
            '101200401', //孝感
            '101200601', //黄石
            '101200701', //咸宁
            '101270101', //成都
            '101270301', //自贡
            '101270401', //绵阳
            '101270501', //南充
            '101270601', //达州
            '101270701', //遂宁
            '101270801', //广安
            '101270901', //巴中
            '101271001', //泸州
            '101271101', //宜宾
            '101271201', //内江
            '101271301', //资阳
            '101271401', //乐山
            '101271501', //眉山
            '101271601', //凉山
            '101271701', //雅安
            '101271801', //甘孜
            '101271901', //阿坝
            '101272001', //德阳
            '101272101', //广元
            '101270201', //攀枝花
            '101170101', //银川
            '101170501', //中卫
            '101170401', //固原
            '101170201', //石嘴山
            '101170301', //吴忠
            '101150101', //西宁
            '101150301', //黄南
            '101150801', //海北
            '101150501', //果洛
            '101150601', //玉树
            '101150701', //海西
            '101150201', //海东
            '101150401', //海南
            '101120101', //济南
            '101120601', //潍坊
            '101120901', //临沂
            '101121001', //菏泽
            '101121101', //滨州
            '101121201', //东营
            '101121301', //威海
            '101121401', //枣庄
            '101121501', //日照
            '101121601', //莱芜
            '101121701', //聊城
            '101120201', //青岛
            '101120301', //淄博
            '101120401', //德州
            '101120501', //烟台
            '101120701', //济宁
            '101120801', //泰安
            '101110101', //西安
            '101110300', //延安
            '101110401', //榆林
            '101111001', //铜川
            '101110601', //商洛
            '101110701', //安康
            '101110801', //汉中
            '101110901', //宝鸡
            '101110200', //咸阳
            '101110501', //渭南
            '101100101', //太原
            '101100701', //临汾
            '101100801', //运城
            '101100901', //朔州
            '101101001', //忻州
            '101100501', //长治
            '101100201', //大同
            '101100301', //阳泉
            '101100401', //晋中
            '101100601', //晋城
            '101101100', //吕梁
            '101130101', //乌鲁木齐
            '101130301', //石河子
            '101130401', //昌吉
            '101130501', //吐鲁番
            '101130601', //库尔勒
            '101130701', //阿拉尔
            '101130801', //阿克苏
            '101130901', //喀什
            '101131001', //伊宁
            '101131101', //塔城
            '101131201', //哈密
            '101131301', //和田
            '101131401', //阿勒泰
            '101131501', //阿图什
            '101131601', //博乐
            '101130201', //克拉玛依
            '101140101', //拉萨
            '101140301', //山南
            '101140701', //阿里
            '101140501', //昌都
            '101140601', //那曲
            '101140201', //日喀则
            '101140401', //林芝
            '101340101', //台北县
            '101340201', //高雄
            '101340401', //台中
            '101310101', //海口
            '101310201', //三亚
            '101310202', //东方
            '101310203', //临高
            '101310204', //澄迈
            '101310205', //儋州
            '101310206', //昌江
            '101310207', //白沙
            '101310208', //琼中
            '101310209', //定安
            '101310210', //屯昌
            '101310211', //琼海
            '101310212', //文昌
            '101310214', //保亭
            '101310215', //万宁
            '101310216', //陵水
            '101310217', //西沙
            '101310220', //南沙岛
            '101310221', //乐东
            '101310222', //五指山
            '101310102', //琼山
            '101250101', //长沙
            '101250301', //株洲
            '101250401', //衡阳
            '101250501', //郴州
            '101250601', //常德
            '101250700', //益阳
            '101250801', //娄底
            '101250901', //邵阳
            '101251001', //岳阳
            '101251101', //张家界
            '101251201', //怀化
            '101251301', //黔阳
            '101251401', //永州
            '101251501', //吉首
            '101250201', //湘潭
            '101190101', //南京
            '101190301', //镇江
            '101190401', //苏州
            '101190501', //南通
            '101190601', //扬州
            '101191301', //宿迁
            '101190801', //徐州
            '101190901', //淮安
            '101191001', //连云港
            '101191101', //常州
            '101191201', //泰州
            '101190201', //无锡
            '101190701', //盐城
            '101050101', //哈尔滨
            '101050301', //牡丹江
            '101050401', //佳木斯
            '101050501', //绥化
            '101050601', //黑河
            '101051301', //双鸭山
            '101050801', //伊春
            '101050901', //大庆
            '101051002', //七台河
            '101051101', //鸡西
            '101051201', //鹤岗
            '101050201', //齐齐哈尔
            '101050701', //大兴安岭
            '101060101', //长春
            '101060301', //延吉
            '101060401', //四平
            '101060901', //白山
            '101060601', //白城
            '101060701', //辽源
            '101060801', //松原
            '101060201', //吉林
            '101060501', //通化
            '101070101', //沈阳
            '101070301', //鞍山
            '101070401', //抚顺
            '101070501', //本溪
            '101070601', //丹东
            '101071401', //葫芦岛
            '101070801', //营口
            '101070901', //阜新
            '101071001', //辽阳
            '101071101', //铁岭
            '101071201', //朝阳
            '101071301', //盘锦
            '101070201', //大连
            '101070701', //锦州

        ];
        
        $client   = new Client();
        $config   = ['allow_redirects' => true, 'debug' => true];
        $requests = function ($total) use ($city_code_arr, $config, $client) {
            for ($i = 0; $i < $total; $i++) {
                $uri = 'http://www.weather.com.cn/data/cityinfo/' . $city_code_arr[$i] . '.html';
                yield new Request('GET', $uri, $config); //或者用下面的yield
                // yield function() use ($client,$uri) {
                //         return $client->getAsync($uri);
                //     };
            }
        };
        $result_arr = [];
        $pool = new Pool($client, $requests(count($city_code_arr)), [
            'concurrency' => 60, //并发数
            'fulfilled'   => function ($response, $index) use (&$result_arr) {
                $result = $response->getBody()->getContents();
                $result_obj = json_decode($result, true);
                if ($result_obj) {
                    if(!empty($result_obj['weatherinfo'])){
                        //$result_arr[]=$result_obj['weatherinfo'];
                        $result_arr[]=$result;
                    }
                } else {
                    dump($index);
                }
                //$result_arr[]=json_decode($result,true);
                //dump($requests);
            },
            'rejected'    => function ($reason, $index) {
                // 失败的请求
                dump($reason);
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        dump($result_arr);
    }
}
