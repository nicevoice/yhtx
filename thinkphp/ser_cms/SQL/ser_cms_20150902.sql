/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : ser_cms

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-09-02 12:20:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `administrator`
-- ----------------------------
DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator` (
  `user_id` int(11) DEFAULT NULL,
  `level` tinyint(1) DEFAULT NULL COMMENT '管理员级别',
  KEY `fk_user_id_administrator` (`user_id`) USING BTREE,
  CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of administrator
-- ----------------------------
INSERT INTO `administrator` VALUES ('2', '1');

-- ----------------------------
-- Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `des` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `category_id` int(11) DEFAULT NULL,
  `src_link` varchar(255) DEFAULT NULL COMMENT '来源地址',
  `src_user_id` int(11) DEFAULT NULL COMMENT '原创者user_id',
  `user_id` int(11) DEFAULT NULL COMMENT '发布者id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `push_time` int(11) DEFAULT NULL COMMENT '发布时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-待发布，1-已发布，-1-删除',
  `expand_json` text COMMENT '拓展内容',
  `zan_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `low_count` int(11) NOT NULL DEFAULT '0' COMMENT 'low数',
  `read_count` int(11) DEFAULT '0' COMMENT '阅读数',
  `picture_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `fk_user_id` (`user_id`) USING BTREE,
  KEY `fk_category_id` (`category_id`) USING BTREE,
  KEY `fk_src_user_id_article` (`src_user_id`) USING BTREE,
  CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `article_category` (`category_id`) ON DELETE SET NULL,
  CONSTRAINT `article_ibfk_2` FOREIGN KEY (`src_user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `article_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1023 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1004', '兴顺夜市', '兴顺夜市北起建设大路，南至沈阳工业大学，全长约1.8公里，一期共设有摊位594个，在规模上是全国最大的夜市。兴顺夜市的营业时间是18时30分至23时，此时兴顺街会封闭交通，成为一条步行街。', '<br/><h5 style=\"font-size: 32px; font-weight: bold; border-bottom: 2px solid rgb(204, 204, 204); padding: 0px 4px 0px 0px; text-align: center; margin: 0px 0px 20px;\"><span style=\"font-size: 20px;\">沈阳兴顺夜市</span></h5><p><span data-are=\"\">号称是全国最长的夜市嘛 也很出名 作为吃货 怎么可以没来过呢</span></p><p><br/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FqBcKAkVMszeEPkgi6Vr2FndJMBZ\" style=\"\" title=\"1438395419518275.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fi4qKUZqhtYPBXBEsVucGDr4oefp\" style=\"\" title=\"1438395419349032.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fv3fLjNnmhBxOhudf-2n4GlSrpPp\" style=\"\" title=\"1438395420486102.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FuUqnG9Qv2s5vYlU5MSeWwizOw-h\" style=\"\" title=\"1438395420997080.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FowiikVNEPNwa5RMT6bmNGRD1N9v\" style=\"\" title=\"1438395420111262.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FlrI2ijXAyJHPqqnwkVU7aDcndtG\" style=\"\" title=\"1438395420689702.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fk2DwhdzvRg8nRnzsA8kOET5BcZN\" style=\"\" title=\"1438395421648083.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FurkqadkHupzXqVK5vgbcTHm9Tox\" style=\"\" title=\"1438395421839402.jpg\"/></p><p><br/></p><p>这个~呵呵~相当于买个杯子~但是确实是新鲜果汁啊~<br/></p><p><br/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FsTwiuMGp3068SxVeHVWOV716i1O\" style=\"\" title=\"1438395421950151.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FlOrGFrYH3MkcnBL7M2ZWj0F5jnb\" style=\"\" title=\"1438395421939003.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fl5ZsoEfgMtYEKESIZ2V8iEoKNn1\" style=\"\" title=\"1438395422526839.jpg\"/></p><p><br/></p><p>看到后感觉自己老了啊~唉~<br/></p><p><br/></p><p><br/></p>', '800000', '', null, '2', '1438395538', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/FlOrGFrYH3MkcnBL7M2ZWj0F5jnb');
INSERT INTO `article` VALUES ('1005', '沈阳彩电塔夜市', '彩塔夜市是沈阳的老牌夜市，如果你没逛吃过彩塔夜市，就不能说自己是一名合格的吃货！东西便宜好吃，最接地气，夜夜爆满。', '<p>沈阳标志建筑彩电塔，位于沈河区，同时那里到夏季的晚上就会有火爆的夜市。</p><p><br/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FqewTs2lRWJZ3YQhlkKIWif-Ybj2\" style=\"\" title=\"1438395997473366.jpeg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FiX1uJp3FQdlfLduxMmXXgQScsd6\" style=\"\" title=\"1438395997970779.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FsetwvDG-7OFnwJgPWs_Oc_X_zSD\" style=\"\" title=\"1438395997482819.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FrwIXycf3iUcSd_5VAJB_XMbtRK5\" style=\"\" title=\"1438395998762176.jpg\"/></p><p><br/></p><p>地点：</p><p><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img alt=\"火狐截图_2015-08-01T02-23-03.737Z.png\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fmxq3V_zndtKXQ2mxWF8jb7Yu-Pq\" title=\"1438396033181392.png\"/></p><p><br/></p><p><br/></p>', '800000', '', null, '2', '1438396137', null, '1', null, '0', '0', '8', 'http://7xkon4.com1.z0.glb.clouddn.com/FtcPZEkvAIT19rKVUj2XQYTrWUc7');
INSERT INTO `article` VALUES ('1006', '万柳塘公园', '万柳塘位于沈阳市东南部，总面积三十一万平方米，因柳树种类和数量的众多而得名。万柳塘在清代被誉为“柳塘避暑”而被列入盛京八景之一。一九五三年沈阳市政府对南运河进行清理并将挖河泥沙在此堆成假山，形成万柳塘公园雏形，一九八四年再次对南运河改造，扩大了万柳塘的水面积并设大门立围栅建柳塘十景正式命名万柳塘公园。', '<p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FszOMjh9GbfeYBqFKfRNKZjHkrJI\" style=\"\" title=\"1438397814380831.png\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FldNEVmxuw2n1VHd5vSuPMdLJhPn\" style=\"\" title=\"1438397814726342.png\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fp3qqUPI8_s_--tMw6TeUXwNLPyx\" style=\"\" title=\"1438397814244304.png\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FjGZ8jQvCllQOfAOZrllvZrUCimm\" style=\"\" title=\"1438397818662464.png\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FoiL39k_DC2Nl2FiCFMc62BN-fyn\" style=\"\" title=\"1438397816163082.png\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fo8K3ZA0bw7SjjApdDVBOtwYMxia\" style=\"\" title=\"1438397816496780.png\"/></p><p><br/></p>', '400000', '', null, '2', '1438397928', null, '1', null, '0', '0', '6', 'http://7xkon4.com1.z0.glb.clouddn.com/FnZURDsnpXkhKqgll6uAZrVCyotj');
INSERT INTO `article` VALUES ('1007', '世界文化遗产明清皇宫：沈阳故宫', '沈阳故宫始建于公元1625年，是满清王朝入关前清太祖努尔哈赤、清太宗皇太极建造的皇宫，又称盛京皇宫。清世祖福临在此即位称帝。沈阳故宫是国家重点文物保护单位，是中国现存最完整的两座宫殿建筑群之一，现已辟为沈阳故宫博物院。北京、沈阳两座故宫构成了中国仅存的两大完整的皇宫建筑群。2004年7月1日。在中国苏州召开的第28届世界遗产委员会会议批准中国的沈阳故宫作为明清皇宫文化遗产扩展项目列入《世界遗产名录', '<p>沈阳故宫按照建筑布局和建造先后，可以分为3个部分：&nbsp;</p><p>东路——为努尔哈赤时期建造的大政殿与十王亭。于一六二五年开始创建，是封建\r\n皇帝举行“大典”和八旗大臣办公的地方。大政殿为八角重檐钻尖式建筑，殿顶满铺黄琉璃瓦且镶绿色剪边，十六道五彩琉璃脊，大木架结构，榫卯相接，飞檐斗\r\n拱，彩画、琉璃以及龙盘柱等，是汉族的传统建筑形式；但殿顶的相轮宝珠与八个力士，又具有宗教色彩。大政殿内的梵文天花，又具有少数民族的建筑特点。在建\r\n筑布局上与十大王亭组成一组完整的建筑群，这是清朝八旗制度在宫殿建筑上的具体反映。<img alt=\"0b7b02087bf40ad1ea0e6ee5572c11dfa8ecceca.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FqDs01UrFhk6ggUdzWjqfzO5EocI\" title=\"1438571432211970.jpg\"/></p><p>中路——大清门、崇政殿、<a>凤凰楼、</a>清宁宫等，于一六二七年至一六三\r\n五年建成，是封建皇帝进行政治活动和后妃居住的地方。凤凰楼于1627年—1635年建成，是当时封建统治者进行政治活动和举行宴会的地方。清宁宫修在三\r\n点八米的高台商，是五间硬山前后廊式建筑，在东次间开门，寝宫和宗教祭祀连在一起，西屋内三面火炕和火地，窗从外关，烟筒设在后面，这是满族的建筑特点。\r\n这些宫殿镶嵌的龙纹五彩琉璃，栩栩如生，雕刻彩画精致生动。中路为清太宗时期续建的大中阙，包括<a>大清门</a>、崇政殿、<a>凤凰楼</a>以及清宁宫、<a>关雎宫</a>、<a>衍庆宫</a>、<a>永福</a><a>宫</a>等。<img alt=\"cc11728b4710b912e4735ff8c3fdfc0392452201.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FtEGr6T5qLOqEycfzsN-Tk-QiEB9\" title=\"1438571444274331.jpg\"/></p><p>西\r\n路——戏台、嘉荫堂、文溯阁和仰熙斋等，于一七八二年建成，是清朝封建皇帝“东巡”盛京（沈阳）时，读书看戏和存放《四库全书》的场所。整个建筑设计和布\r\n局，反映了皇帝的所谓“尊严”和严格的封建等级制度。在当时的社会条件下，封建统治者建筑这样规模的宫殿，给劳动人民带来了巨大的痛苦和灾难，驱使成千上\r\n万的工匠和农民，日以继夜的劳动，木材要到浑河上游的丛山峻岭的原始森林去砍伐，砖瓦要从三百多华里的海州（今辽宁省海城）烧制，耗费无数人力畜力运到沈\r\n阳。故宫的每座殿宇，一砖一瓦，一石一木，都凝结着劳动人民的智慧和血汗，修建这座宫殿所耗费的人力物力无法统计，仅用砖瓦一项就折银六十八万两，约合当\r\n时四十五万贫苦农民一年的口粮。<img alt=\"eaf81a4c510fd9f9658afc6f252dd42a2834a413.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhoGcNI4NHIQnbVGp5UamEkno22z\" title=\"1438571454352204.jpg\"/></p><p>沈阳故宫博物院不仅是古代宫殿建筑群，还以丰富的珍贵收藏而著称于海内外，故宫内陈列了大量旧皇宫遗留下来的宫廷文物，如努尔哈赤的剑，皇太极的<a>腰刀</a>和<a>鹿角椅</a>等。</p><p>清代入关前，其皇宫设在沈阳，迁都北京后，这座皇宫被称作“陪都宫殿”、“留都宫殿”。后来就称之为沈阳故宫。</p><p>沈阳故宫是我国仅存的两大宫殿建筑群之一，占地面积6万多平方米，它的规模比占地72万平方米的北京故宫要小得多，但是它在建筑上有自己的特色。沈阳故宫是后金第一代汗努尔哈赤开始修筑。努尔哈赤驾崩后，第二代汗皇太极继续修建成功。<img alt=\"8718367adab44aedd43d9e7cb31c8701a18bfb33.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FkhPy7yI9wZko6-GHKvHTUn84VNZ\" title=\"1438571467889826.jpg\"/></p><p><br/></p>', '400000', 'http://baike.baidu.com/link?url=CT3b7utb0ehj6pXsOXQLyWAqQ-FL7AAqBrfahU9tRFtZJOYgQuWKI9r9VLKMXI4TJ6OVeN7XaXvdtwY2uLs7da', null, '2', '1438571474', null, '1', null, '0', '0', '2', 'http://7xkon4.com1.z0.glb.clouddn.com/FlKb4cdV3Wfku2F76nujNcmUzUEF');
INSERT INTO `article` VALUES ('1008', '清昭陵（北陵）', '清昭陵是清朝第二代开国君主太宗皇太极以及孝端文皇后博尔济吉特氏的陵墓，占地面积16万平方米，是清初“关外三陵”中规模最大、气势最宏伟的一座。位于沈阳（盛京）古城北约十华里，因此也称“北陵”，是清代皇家陵寝和现代园林合一的游览胜地。园内古松参天，草木葱茏，湖水荡漾，楼殿威严，金瓦夺目，充分显示出皇家陵园的雄伟、壮丽和现代园林的清雅、秀美。昭陵除了葬有帝后外，还葬有麟趾宫贵妃、洐庆宫淑妃等一批后妃佳丽', '<p>盛京之陵，规模最大保存最完整的就是昭陵。<br/><img alt=\"7aec54e736d12f2e3c7a62204bc2d56285356877.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fh3uNV-X7THINTS-1zT3YDQw4EME\" title=\"1438571827354489.jpg\"/>沈阳昭陵陵区建筑布局大致是这样的：陵区四周设有红、白、青三种颜色界桩，其南面还备有挡众木（又叫“拒马木”）四百四十二架。陵区南北狭长，东西偏窄。陵区最南端是下马碑，其次，为华表和石狮。计有下马碑四座，华表一对，石狮一对，它们分别立在道路的两旁。石狮之北建有神桥。神桥之西原有涤品井一眼。神桥往北为石牌坊。石牌坊东西两侧各有一座小跨院。东跨院是皇帝更衣亭和静房（厕所）。西跨院是省牲亭和馔造房。石牌坊以北是陵寝正门——正红门，此门周围是环绕陵区的朱红围墙，又叫“风水墙”。正红门内有一条南北笔直的石路叫“神道”，神道两侧由南往北依次立有擎天柱柱一对，石狮子一对，石獬豸一对，石麒麟一对，石马一对，石骆驼一对，石象一对。这些石兽统称“石象生”。再往北，在神道正中有神功圣德碑亭一座。碑亭两侧有“朝房”，东朝房是存放仪仗及制奶茶之地，西朝房是备制膳食和果品之所。碑亭之北是方城，方城正门曰“隆恩门”，城门上有楼，俗称“五凤楼”。方城正中是隆恩殿，两侧有配殿和配楼。配楼俗名“晾果楼”，晾晒祭祀用果品之处。隆恩殿后有二柱门和石祭台，再后是券门，券门顶端有大明楼，步入券门是月牙城，月牙城正面有琉璃影壁，两侧有“蹬道”可上下方城，月牙城之后是宝城、宝顶，宝顶之内为地宫。宝城之后是人工堆起的陵山——“隆业山”。另在陵寝西侧、与宝顶遥遥相对还有一组建筑叫“懿靖大贵妃、康惠淑妃园寝”，是安葬太宗众妃的莹地。除此之外，在陵寝东西两翼各三里许有陪葬墓，左侧有武勋王杨古里墓及奶妈坟，右侧有贞臣敦达里及安达里殉葬墓。这种以功臣陪葬的形式是古代陵寝制度，体现了封建君王“事死如事生”的愿望，也体现了忠君思想和严格的封建等级制度。<br/><img alt=\"eac4b74543a98226cdd6e7678e82b9014a90eb5f.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fgb0s1q0KWW-ifWmfEMwPQmty5_f\" title=\"1438571762294867.jpg\"/>另在陵区之外还有藏经楼、关帝庙、点将台等建筑。昭陵建筑布局严格遵循“中轴线”及“前朝后寝”等陵寝规制，陵寝主体建筑全部建在南北中轴线上，其它附属建筑则均衡地安排在它的两侧。这样的设计思想主要是体现皇权至高无上，同时，达到使建筑群稳重、平衡及统一等美学效应。<br/><img alt=\"a6efce1b9d16fdfa3d180aaeb08f8c5494ee7b7e.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FpZZf6G7VBRrsIUUiDOQchTSUeAW\" title=\"1438571774928979.jpg\"/>昭陵的管理有文武两大衙门。一个叫总管衙门，一个叫关防衙门，管衙门主要负责陵区的防卫，关防衙门负责祭祀和陵寝建筑的一般修缮。<br/><br/>清朝逊国之后，昭陵虽然仍由三陵守护大臣负责管理，但由于连年战乱，国库入不敷出，对昭陵无力做大的修缮，以至陵园建筑残破凋零。当时有位文人写过这样一首《游北陵》诗：“涉足昭陵户与庭，辉煌眩目未曾经。莓苔满径无人管，杨柳山中犹自清。”写出了当时昭陵的真实面貌。清代“陪京（沈阳）八景”里有“北陵（昭陵）红叶”。金梁在《奉天古迹考》中说：“北陵多枫柳，西风黄叶红满秋林，故名北陵红叶。”<br/><img alt=\"6d81800a19d8bc3e0144ca8a868ba61ea8d34526.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fva3doJAoy5Ce2W0TrxzFPa2X98H\" title=\"1438571799498094.jpg\"/>总的来看，沈阳昭陵主体建筑仍保存至今，地下基础完好，规划、布局依然完整，古建筑与遗址未受后人过多的干预与改变，自然环境也基本保持原始状态，真实性与完整性程度很高。</p>', '400000', 'http://baike.baidu.com/link?url=_AawHbZVW-dgp0BmyTCZGzczExHDfy-ds-NGeXT44kJTCuPODAtGquMxjxuT6zPeqa6HKXCiHiZ1ZC1s8CcWZa', null, '2', '1438571839', null, '1', null, '0', '0', '3', 'http://7xkon4.com1.z0.glb.clouddn.com/FuVSZov6P3y_UbNwxxZm9-fUt6L_');
INSERT INTO `article` VALUES ('1009', '世界文化遗产明清皇陵：沈阳东陵（福陵）', '沈阳东陵，又称“福陵”，是清太祖努尔哈赤和孝慈高皇后叶赫那拉氏的陵墓，位于沈阳市区东北部丘陵地带，距市区约十八公里。\r\n东陵是沈阳名胜古迹之一，前临浑河，后倚天柱山，万松耸翠、大殿凌云，占地19.48万平方米，具有我国古代建筑艺术传统和满族文化风格。', '<p>关于陵园<br/><br/>福陵是清太祖努尔哈赤及其孝慈高皇后叶赫纳喇氏的陵墓。与沈阳市的昭陵、新宾县永陵合称“关外三陵”、“盛京三陵”。东陵公园始建于公元1629年 (天聪三年)，到公元1651年基本建成。后经清朝顺治、康熙、乾隆年间的多次修建，形成了规模宏大、设施完备的古代帝王陵墓建筑群。距今已有三百六十余年历史。崇德元年(公元1636年)大清建国，定陵号为“福陵”，1929年被当时奉天当局辟福陵为东陵公园。<br/>环境<br/><img alt=\"86d6277f9e2f0708efbc536eec24b899a901f227.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FpIyOv5KJ1UgJ9sVzwYnLeTqiWht\" title=\"1438572037192782.jpg\"/>东陵公园地处于沈阳市东郊二十里的天柱山上，整个占地面积为557．3公顷，其中陵寝占地为19公顷，整座陵墓背倚天柱山，前临浑河，自南而北地势渐高，山形迤逦、万松参天、众山腑伏、百水回环、层楼朱壁、金瓦生辉、建筑宏伟、气势威严、幽静肃穆、古色苍然，其优美独特的自然风光和深邃人文景观早已为历代文人雅士所垂青。福陵建筑格局因山势形成前低后高之势，南北狭长，从南向北可划分为三部分：大红门外区、神道区、方城、宝城区。陵寝建筑规制完备，礼制设施齐全，主要建筑规模宏伟，陵寝建筑群保存较为完整。<br/>规模<br/><img alt=\"b21bb051f8198618cb28d5c64aed2e738bd4e601.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FtBUq_1l0B7cd_MafIX8aKNlkzbY\" title=\"1438572040726557.jpg\"/><br/></p><p>陵园坐北朝南，四周围以红墙，南面中央为单檐歇山式正红门三楹，拱门三道。门内参道两侧成对排列着石狮、石马、石驼、石虎等石雕。平地尽头，利用天然山势修筑了一百零八蹬石阶，以象征三十六天罡和七十二地煞。过了石桥，正中为碑楼，重檐歇山式，四面券门，下为须弥座式台基，内立清圣祖玄烨亲撰的“大清福陵神功圣德碑”，碑文用满、汉两种文字书刻，记载着努尔哈赤的功绩。<br/><img alt=\"d1160924ab18972b05643a88e6cd7b899e510a11.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FmkqMrQadeJ_vrvB9Vtt5J1Iz1gM\" title=\"1438572065351602.jpg\"/>两侧耸立着成对的华表、骆驼、狮子、马、虎等石雕。蹬上一百零八级台阶，有一座形似城堡的“方城”，这是陵园的主体，方城后面为月牙形宝城，宝城正中是突起的宝顶，下面是埋葬努尔哈赤和叶赫那拉氏的地宫。气势宏伟，古朴典雅，清幽肃穆，令东陵依山傍水人流连忘返。<br/><br/>再北的陵园的城堡式建筑叫方城，四角建有角楼。方城南面正中建有隆恩门，门楣上用汉、满、蒙三种文字刻成“隆恩门”三字。进门迎面为隆恩殿，是祭祀用的享殿，殿后洞门之上设明楼，内立“太祖高皇帝之陵”石碑。方城后为圆形宝城，两城间呈月牙状，因而也叫月牙城。宝城正中有一突起的宝顶，下为埋置灵柩的地宫。福陵建筑群是劳动人民血汗和智慧的结晶，它将我国传统建筑形式与满族建筑形式融为一体，形成了异于关内各陵的独特风格，很多人很喜欢这种设计，因此有很多的人去参观。</p>', '400000', 'http://baike.baidu.com/view/753271.htm', null, '2', '1438572197', null, '1', null, '0', '0', '7', 'http://7xkon4.com1.z0.glb.clouddn.com/FtYfzL-fd_Wppg1hh487Qh8X0LtF');
INSERT INTO `article` VALUES ('1010', '中共满洲省委旧址', '满洲省委在沈阳和平区皇寺路福安巷3号（原北市场福安里4号）有四间青砖瓦房，看似平常的房子，在1927年一l929年却是东北地区革命运动的指挥中心。', '<p>1927年党的“八七”会议后，党中央为了统一对东北党组织的领导，决定派陈为人组建中共满洲省委。同年10月在哈尔滨召开了东北地区第一次党员代表大会，宣告中共满洲省临时委员会成立，省委机关设在奉天（沈阳）。1928年9月，中共满洲省临委在沈阳召开了东北地区第三次党员代表大会，大会决定将中共满洲省临委改为中共满洲省委。陈为人、刘少奇、陈潭秋、罗登贤等先后担任省委书记。中共满洲省委旧址内设有刘少奇旧居纪念馆。中共满洲省委旧址自1986年对外开放以来，共接待国内外观众20万人次，现已成为进行党史教育和爱国主义教育的重要场所。</p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fjso7J0o6U5oQohsq0HeYup5sUVb\" style=\"\" title=\"1438572381518267.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FpXlqPLY60Z37EXTG3VdZEbMKssv\" style=\"\" title=\"1438572381482778.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fsy6SrmPjcH4D7BzGoy131qbOvz2\" style=\"\" title=\"1438572381856839.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FjA9uwHQSefEPM38mbMe52cDsdkI\" style=\"\" title=\"1438572381728484.jpg\"/></p><p><br/></p>', '400000', 'http://baike.baidu.com/view/476454.htm', null, '2', '1438572396', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/Fl2O5jhmjKxT5GiFEBcs0qJ4Ozoz');
INSERT INTO `article` VALUES ('1011', '张氏帅府', '张氏帅府，又称“大帅府”或“少帅府”，位于辽宁省沈阳市，是张作霖及其长子、伟大爱国者张学良的官邸和私宅。\r\n张氏帅府始建于1914年，总占地3．6万平方米，总建筑面积为2．76万平方米。2002年更名为张氏帅府博物馆暨辽宁近现代史博物馆，主要有大、小青楼、西院红楼群及赵四小姐楼等。 是迄今东北地区保存最为完好的名人故居，是全国重点文物保护单位。', '<p>张氏帅府是由东院、中院、西院和院外建筑等四个部分组成的庞大建筑群，其中既有中国传统风格的<a>四合院</a>、水榭亭台的帅府花园，又有欧式风情的大青楼、边业银行、红楼群，以及中西合璧式的小青楼和<a>赵四小姐</a>楼。1991年，张氏帅府被列为我国优秀近代建筑群。</p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FmKzdDHZSeWHzxcAKunC2lkEJacd\" style=\"\" title=\"1438572541233799.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FoZsMJezvXH9LHOsdMlUKb91I4QM\" style=\"\" title=\"1438572541744863.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FiHOkCW58G7KYwilhHMrBjA6JiB9\" style=\"\" title=\"1438572542993105.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fh9ZSA64Ks4rtWQYtHi_KY1lrkec\" style=\"\" title=\"1438572541620277.jpg\"/></p><p><br/></p>', '400000', 'http://baike.baidu.com/view/17173.htm', null, '2', '1438572557', null, '1', null, '0', '0', '9', 'http://7xkon4.com1.z0.glb.clouddn.com/FuHGB5-RDFBnZo-o0ZjoabnWVIIq');
INSERT INTO `article` VALUES ('1012', '“九·一八”历史博物馆', '“九·一八”历史博物馆位于沈阳市大东区望花南街46号，地处沈阳东北部，柳条湖立交桥西北，西靠长大铁路，是国内外迄今为止唯一全面反映“九·一八”事变历史的博物馆，现为国家级爱国主义教育基地、国家4A级旅游景区。', '<p>“九·一八”历史博物馆是在原“九·一八”残历碑和地下展厅的基础上扩建而成，1999年9月18日正式开馆，并由江泽民同志题写了馆名。“九·一八” 历史博物馆是一本翻开的“台历”，台历的左面刻着</p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fjky6fXiE_ZmStKpsWu0jgsrmuNW\" style=\"\" title=\"1438578494974906.jpg\"/></p><p>“九·一八”历史博物馆是在原残历碑和地下展厅的基础上于1997年9月开始</p><p>扩建的，1999年9月18日正式落成开馆。新馆总占地\r\n面积35000平方米，建筑面积12600平方米，开放面积9180平方米，展线长510米。共设有7个展厅，展览照片800余幅；实物300余件；文\r\n献、档案资料近100件；大小型场景19组；雕塑4尊；油画、国画等20余幅，电脑触摸屏14台、大屏幕电视录像机2台。并采用了现代科学技术，配备有分\r\n区广播、中央空调、影视报告厅、电子阅览室、多媒体电脑系统及国际互联网等设施，是一座大型的现代化的历史博物馆。</p><p><br/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FnfwtKdIYouFdSxoSYXW0oi7UKtY\" style=\"\" title=\"1438578495950120.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FksOxQckDD3bOI5ZvNTBTL7ZvZuh\" style=\"\" title=\"1438578494708644.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FoE78IIulT_Q5Xghq58gGHCTtLSJ\" style=\"\" title=\"1438578495169673.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fjky6fXiE_ZmStKpsWu0jgsrmuNW\" style=\"\" title=\"1438578495918495.jpg\"/></p><p><span style=\"color: rgb(255, 0, 0);\"><strong>在每年的9月18日前后，“九·一八”历史博物馆都会举办一些纪念活动，此时出行参观，会有不同的感受和收获。</strong></span><br/></p>', '400000', 'http://baike.baidu.com/view/174294.htm', null, '2', '1438578559', null, '1', null, '0', '0', '6', 'http://7xkon4.com1.z0.glb.clouddn.com/FoyKxLomStD7VoEROsm1iMbLxFTh');
INSERT INTO `article` VALUES ('1013', '彩电塔', '辽宁广播电视塔坐落于沈阳市沈河区青年大街南运河带状公园湖畔。彩电塔塔高305.5米，钢筋混凝土结构，曾被誉为亚洲同类结构第一高塔和东北地区最高的建筑。1984年8月8日破土动工，1989年9月建成投入使用，是集旅游观光、餐饮娱乐、广播电视发射为一体的多功能电视塔。获得建筑“鲁班”奖，是沈阳城市标志性建筑，被列为辽宁省“五十”佳和沈阳市“十五”佳旅游景点。', '<p>彩电塔承担播出中央电视台两套、辽宁电视台两套、沈阳电视台两套、辽宁教育电视台一套共七套电视节目，以及播出中央人民广播电台三个频率、辽宁电台五个频率、沈阳电台两个频率共有十套调频广播节目；并向辽宁全省八个方向(正反向)传播广播电视节目信号、向中央电视台回传辽宁新闻，广播电视发射覆盖沈阳市及周边地区。而2013年第十二届全运会将在辽宁召开，届时辽宁将承担重要的广播电视信号的发射任务，而彩电塔无疑将成为传播发射全运会信号和频率的重要节点。</p><p><img alt=\"b999a9014c086e06d816501e02087bf40ad1cb2d.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fuo1WRuWLuY1Wkg48PAvVEyS_KPS\" title=\"1438578768855967.jpg\"/></p><p>观光厅——位于塔楼196米处，面积800平方米，乘高速电梯40秒即可到达，内设游艺厅、旅游纪念品商场，其中可容纳200余人的空中休闲酒吧可举办生日婚礼庆典及各种形式的娱乐活动。<br/></p><p><img alt=\"63d0f703918fa0ecdd790b58269759ee3d6ddb2d.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FtMExUEZ6inErvok-SZ0KXzIsMcc\" title=\"1438578790495765.jpg\"/></p><p>旋转餐厅——位于塔高193米处，面积400平方米，可同时容纳200人就餐，是沈城最高美食府，由名厨主理的辽、川、粤菜及彩塔饺子享誉海内外，风格各异的包房典雅舒适。转台旋转一周45分钟，客人可边就餐边领略沈城无限风光。旋转餐厅承办空中婚礼宴席，宴会来宾免费登塔观光游览。<br/>露天观光平台——位于塔楼205米处，面积900平方米，设置高倍望远镜免费供游人观看。登临平台，心旷神怡，沈城全貌尽收眼底。夜色中的电视塔，立体照明系统绚丽多彩美不胜收。</p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FiC9spxExDZrFTVfOCACjYNrnGnT\" style=\"\" title=\"1438578813663020.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fk46f4diccMcXuvsDIGLltyMDpCv\" style=\"\" title=\"1438578815511920.jpg\"/></p><p>彩电塔塔楼台阶1280级、长期开展“超越自我、勇攀高峰”为主题的全民健身徒步登塔活动。<br/></p>', '200000', 'http://baike.baidu.com/view/507793.htm', null, '2', '1438578832', null, '1', null, '0', '0', '5', 'http://7xkon4.com1.z0.glb.clouddn.com/FrmsiRaGVj0LEozsPH5-vVfJiKui');
INSERT INTO `article` VALUES ('1014', '沈阳中街', '中街，中国第一条商业步行街，中国十大著名商业街，全国首批百城万店无假货示范街，迄今为止中国内地最长的商业步行街。', '<p>她是沈阳最早形成的商业中心。1625年（明天启五年、后金天命十年）至1631年（明祟帧四年、后金天聪五年），后金将明朝所筑砖城进行改建扩建，按照中国历史上流传的“左祖右社、面朝后市”之说，将原来的“十”字型两条街改筑为“井”字型4条 街。即今沈阳路、中街路、朝阳街、正阳街。 当时，中街路称四平街，东西两侧建有钟 楼、鼓楼各一座。街长579．3米，宽11．7 米。 如今的中街已成为我市第一条步行商业街，街道两旁店铺鳞次栉比，买卖兴隆，一派欣欣向荣之意。<br/><strong>现况</strong><br/>中街位于沈阳市中心城区沈河区，世界文化遗产之一---清沈阳故宫南侧。厚重的历史积累与文化沉淀形成了独具皇城特色的购物休闲场，最初的中街指的是西起正阳街东至东顺城街，约900米的步行街。随着时代的变迁，沈阳城市商业的快速发展及各大开发商的入驻，将原中街以西的沈河区西顺城街至正阳街路段及大东区的东顺城街至小什字街路段共同并入中街商业区。截至2015年初，中街商业街区由中街路与小东路组成，全长为1500米的国内最长商业步行街。市委市政府未来规划将西顺城街至正阳街路段为步行街，取消机动车运行，公交车调流。届时中街全长将达到1900米，大商业规模将更加完善。<br/><strong>沿街商铺</strong><br/>西段<br/>中街九龙港：项目总建筑面积10万平方米，规划地上六层，地下二层。B2层为机动停车场；B1层主营小百货、饰品、牛仔、箱包等；地上1至2层为各快时尚女装经营区；3层为鞋区及儿童、母婴区；4至5层为韩尚中心，主营韩国服饰、化妆品、家居用品等；6层为美食广场。“轻奢华，快时尚，乐体验，网天下” ，九龙港商品主要以时尚、潮流女性服饰为主线，涵盖食品、日用品、生活用品、饰品、 化妆品、鞋、箱包、皮具、儿童、食品、餐饮等多品类、多品种、多风格、多功能商品以及服务组合。九龙港以女性快时尚商品为主题，以韩尚中心、智慧商城为创新，以情景卖场为特色，打造以都市时尚生活方式体验中心为模式的快时尚主题智慧商城。<br/>久光百货：久光百货自营业以来，深受顾客青睐，销售节节攀升。引进国外百货管理经验，融合香港崇光百货开店21周年来的灵活而严谨的管理模式、日本商业无微不至的服务文化、以及广阔的采购网络和业务联系。引入了“店中 店”、日式亲切服务、以“客”为尊的精神“，以及先进的“一站式”购物理念，提供购物餐饮、休闲娱乐、仪容护理、音乐培训等一系列配套服务，彰显久光致力在繁华闹市中为顾客提供悠闲生活空间，让久光百货成为目前上海最具亲和力的百货之一<br/>中段<br/>皇城恒隆广场：由恒隆地产有限公司投资建设，总楼面面积达109300平方米，坐落于沈阳市的商业和文化枢纽中街。皇城恒隆广场的建筑外型揉合世界文化遗产“沈阳故宫”的建筑元素，志在打造恒隆地产在内地发展的另一个新地标物业。在整体定位上与其他恒隆广场略有点不同，不单追求奢侈品牌，更追求满足年轻时尚的消费群体购物休闲的全方位需要。全新开业的沈阳皇城恒隆广场将开设多家倍受时尚潮人青睐的品牌旗舰店，使这里成为流行追逐者必到的潮流“圣地”。广场四楼开设的十多家面向中高端群体的餐饮品牌，可以让您在购物之余大快朵颐，充分满足味蕾的需求。此外，四楼餐饮区南侧还设计规划了一个精致的室外花园平台，两侧林荫掩映，提供丰富多样的景观和体验，顾客可能会想花上大半天或者更长的时间在这里聚会、用餐、流连，享受中街这珍贵的绿色空间<br/>兴隆大家庭：隶属于辽宁兴隆大家庭商业集团，是该集团的主力企业。2001年，辽宁兴隆大家庭商业集团收购当时中国最大的单体商场——沈阳东亚广场后，将其组建成沈阳首家摩尔、巨型购物中心。与沈阳故宫一墙之隔，面积19.7万平方米，是一艘名副其实的商业航母。<br/>沈阳第二百货商店：拥有百年历史的品牌老店<br/>沈阳春天：奢侈品、名品、日用百货、服饰、时尚鞋帽箱包<br/>荟华楼：1998年，荟华楼由一家小型国有金店转制成为沈河区第一家股份合作制企业。公司自转制以来，本着“超越自我，争创第一”的企业精神，以打造高质量的产品、良好的品牌形象、周到的特色服务和科学的管理为目标，实现了跳跃式的发展。使公司在短短的几年的时间发展成年销售额超7亿元，上缴税金近4000万元的国内一流的现代珠宝首饰企业，商场零售面积达10000多平方米，安排就业1000余人，其销售连续多年在东北三省同行业中列居榜首，成为全国黄金珠宝饰品行业中的佼佼者。<br/>沈阳商业城：曾连续4年跻身全国十大商业企业行列。总营业面积11.5万平方米，多年来，与国内外数千家知名企业建立闻长期的业务往来关系，经营国际、国内知名品牌与名优畅销商品10万余种。<br/>罕王百货01流行馆：青春流行购物馆，位于中街商业城对面，现代化建筑特色明显，主打年轻人消费为主的时尚品牌。<br/>运动汇：胜道体育在中街的最大商场之一，由原来的好利来商场改造而成，经营国内外各种潮流、运动、休闲服饰，品种齐全。<br/>东段<br/>大商新玛特：大商集团旗下的新玛特购物广场，位于中街与东顺城路交叉口上，地理位置优越，及购物、娱乐、超市、餐饮为一体的大型购物中心。<br/>中粮大悦城：位于东中街的末端，由央企中粮集团投资建成的超大型购物、休闲、餐饮、娱乐广场，有两栋建筑通过空中天桥连接而成，“中粮-沈阳大悦城”秉承大悦城的品牌概念，着力于打造国际现代化的精品购物中心。 由A、B、C、D四馆和沃尔玛超市构成，是以国际化的大型Shopping Mall作为核心的主题购物中心，集购物、餐饮、娱乐、文化、休闲于一体。<br/>新世界百货：新世界集团是建基於香港的知名企业，以物业 、电讯等核心业务为主。稳健的财务状况、多元化的业务组合、因时制宜的商业策略，使新世界集团变得更具实力，成为香港地位举足轻重的大财团。新世界集团看准市场契机，于1993 年在天时地利人和的条件下成立了新世界百货有限公司，从此，新世界百货拉开了全力进军中国零售业市场的帷幕。<br/>其他商业店铺<br/>北方国际黄金珠宝交易中心、亚玛达电器、荟华金店、苏宁电器、亨吉利世界名表中心、欧米茄、国美、地一大道等<br/>食住配套<br/>餐饮独立店铺：老边饺子馆、庆丰包子铺、李连贵熏肉大饼、中街冰点城、大舞台美食街、肯德基、汉堡王、麦当劳、吉野家、李先生等<br/>商场美食广场：兴隆大家庭、皇城恒隆广场、久光百货、01流行馆、中兴新一城、大悦城等<br/>酒店：玫瑰大酒店、天伦瑞格酒店、璟星大酒店、龙之梦丽晶大酒店、中粮公寓、如家、汉庭、锦江之星等</p>', '700000', 'http://baike.baidu.com/view/92431.htm', null, '2', '1438579251', null, '1', null, '0', '0', '5', 'http://7xkon4.com1.z0.glb.clouddn.com/Fi2dJSCWpHR-1eVgLBgmk4PlrF5p');
INSERT INTO `article` VALUES ('1015', '沈阳太原街', '沈阳太原街商业步行街，位于沈阳市和平区，毗邻沈阳站，是中国最著名商业街之一。东北最有影响力的时尚潮汇地，影响力辐射整个东北亚。进入新世纪以后，它是完全仿造日本东京银座商业区的规划模式改造而成。', '<p><strong>特色</strong><br/>太原商业街是位于沈阳市中心地带的繁华商业区，以大型综合百货商店为主体，以专业商店为补充，以是集商业、饮服、文化娱乐为一体的多功能的商业社区，全长3950米，其中，北起中山路、南至市文化宫的近千米长地段，已经成为沈阳市最繁华的商业街区。<br/>从19世纪初，仅有的10几家油盐杂货店铺，发展到21世纪的今天，太原街已经拥有了数百家临街店面、十几座现代化大型商场、数个国内闻名的大型专业市场和一条繁华似锦的商业步行街。这里的日客流量达到百万，日交易额过亿，成为了沈阳名副其实的商业繁华一条街。</p><p><br/></p><p><br/></p><p><img alt=\"eac4b74543a98226f282dc038a82b9014a90eb57.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FqyRyc1DdxBdo-W3-TEpFQ2eOQ1b\" title=\"1438579438786907.jpg\"/></p><p><br/></p><p><strong>商业分布</strong><br/><br/>中山路：秋林公司、凤泰皮草商厦、大公名表城、东北最大的新华购书中心、沈阳古玩城、中山皇冠假日酒店、辽宁宾馆、北约客维景国际大酒店、北约客中心、岷山饭店<br/><br/>太原街：中兴—沈阳商业大厦、北京华联商厦、兴隆一百、沈阳工艺美术中心、盛贸饭店、塞隆眼镜城、美特斯邦威东北旗舰店、城开中心（在建）<br/><br/>中华路：万达广场、金三角广场、金杯商务酒店、新世界酒店、新世界百货、新世界中心、百盛、沃尔玛、国美新活馆、沈阳萃华金店、卓铭国际眼镜城、亨吉利世界名表中心、沈阳欧亚联营公司，潮汇购物中心、富丽华大酒店</p><p><br/></p>', '700000', 'http://baike.baidu.com/subview/515368/16537201.htm', null, '2', '1438579527', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/FrHhJEV3GrD39gSFSfENR2QPfeMR');
INSERT INTO `article` VALUES ('1016', '三好街', '三好街（Sanhao Street），又名“中国电脑软件城”（China Computer Town）。位于辽宁省沈阳市，地处和平区，是辽沈乃至整个东北地区的电脑与IT产品的经销集散地，“中国IT市场指数”下属六大区域监测站所在地之一，中国北方电子信息产品与技术中心商务区[1] ，2005年入选首批十大“中国特色商业街”。全长2.7千米，总占地面积30公顷。', '<p>“中国电脑软件城”始建于1988年，原是“沈阳高新技术产业开发区”（国务院首批批准的“国家高新技术产业开发区”）的重要组成部分。后随2001年1月,以“沈阳高新技术产业开发区”为基础,设立沈阳市“浑南新区”。故虽其地处和平，却现归浑南新区管辖，管理规范，“三好街管理局”曾被评为“2009全国商业街先进集体”，综合地位与重视程度可见一斑。<br/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fi9VZgBmBCsQ4lM8RqNi9iCxPZJw\" style=\"\" title=\"1438579741240390.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FlZk6eyfQD5O_Eo4eRvBE8MJNm6j\" style=\"\" title=\"1438579741151054.jpg\"/></p><p><br/>2014年10月17日，为改变三好街地区发展空间不足、多头管理等问题制约该地区发展，沈阳市通过《关于调整沈阳科技商城管理体制的意见》，决定自11月1日起，将三好街地区的管理体制作以调整，即该地区的行政、经济和社会管理权限全部调整由和平区政府实行统一管理。</p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/Fqfs6DvE_T0cdea2tZ0WA8yqnndO\" style=\"\" title=\"1438579762350065.jpg\"/></p><p><img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FujNN8LOb4Tane4ltL2c2oWWAQYH\" style=\"\" title=\"1438579762269614.jpg\"/></p><p><br/></p>', '700000', 'http://baike.baidu.com/view/555687.htm', null, '2', '1438579803', null, '1', null, '0', '0', '5', 'http://7xkon4.com1.z0.glb.clouddn.com/Fq9F6_pdx2q7d1iwUWcaTT36o1Tz');
INSERT INTO `article` VALUES ('1017', '“大沈阳”信息征集活动', '俺是沈阳人~俺就是稀罕沈阳~', '<p>1.你知道好玩的地方吗？</p><p>2.你知道哪有吃货的聚集地吗？</p><p>3.你知道沈阳的特产吗？</p><p>4.你知道去哪买好看的衣服吗？</p><p>5.你知道哪里环境优美吗？</p><p>6.你知道哪里可以钓鱼吗？</p><p>7.你知道晚上该去哪里溜达吗？</p><p>8.你知道哪里的广场舞最霸气吗？</p><p>9.你知道去哪运动吗？</p><p>&nbsp;&nbsp;&nbsp;&nbsp;…………</p><p>n.你知道吗？</p><p><br/></p><p>知道就快来告诉大家吧~</p><p><br/></p><p>目前您可以发邮件到&nbsp; <a textvalue=\"sd_net@yeah.net\" title=\"sd_net@yeah.net\" target=\"_blank\" href=\"mailto:sd_net@yeah.net\">sd_net@yeah.net</a></p><p><br/></p><p>也可以关注微信： sd_net</p><p>微信二维码：</p><p><img alt=\"123.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg\" title=\"1438668956736209.jpg\"/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; <br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;<br/></p>', '1', '', null, '2', '1438669169', null, '1', null, '0', '0', '122', 'http://7xkon4.com1.z0.glb.clouddn.com/FtQVHfVmiPwTHDfdF9aLdHdQYKLY');
INSERT INTO `article` VALUES ('1018', '老边饺子', '老边饺子是驰名中外的沈阳市汉族传统名小吃，它历史悠久，从创制到现在，已有180多年历史。老边饺子之所以久负盛名，主要是选料讲究，制作精细，造型别致，口味鲜醇，它的独到之处是调馅和制皮。我国著名的艺术大师侯宝林先生亲临品尝老边饺子，吃得兴致勃勃，称赞不已，席间余兴未尽，挥毫写了八个大字：“边家饺子，天下第一。”', '<p>特色<br/><br/>老边饺子由于皮薄肚饱，柔软肉头，馅鲜味好，浓郁不腻，因此，凡远近来沈客人，都愿品尝。1964年邓小平同志到沈阳视察时，品尝过边霖包的饺子，吃后非常高兴地说：“老边饺子有独特之处，要保持下去”。1981年夏天，我国著名的艺术大师侯宝林先生亲临品尝老边饺子，吃得兴致勃勃，称赞不已，席间余兴未尽，挥毫写了八个大字：“边家饺子，天下第一。<br/>主打<br/><br/>面粉、猪肉。<br/>选料<br/><img alt=\"908fa0ec08fa513df33d05af3d6d55fbb2fbd928.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FsVdIAiBCN9NZXNry-mCtKkMJ7xw\" title=\"1438652787418492.jpg\"/>按不同时令科学配比，精致而成。初春选韭菜、大虾配馅，味鲜溢口；盛夏用角瓜、冬瓜、芹菜，可以解腻；深秋选油椒、芸豆、黄瓜、甘兰配馅，清爽可口；寒冬用喜油的大白菜配馅，松散鲜香。至于肥瘦肉的用量一般是春、夏多用瘦，秋、冬多用肥肉与菜的比例，或三七、或四六，这样精致出的饺子口感极佳。<br/>特点<br/><img alt=\"b58f8c5494eef01f038471b4e0fe9925bc317d2a.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FgoUEpVEvBKIvqBHCIiGMXDaE6qL\" title=\"1438652798373493.jpg\"/></p><p>老边饺子是沈阳市著名的地方小吃，1828年从河北传入沈阳，作馅非常讲究，先将煸炒绞碎的猪肉，配以鸡汤或骨头汤煨制，上笼蒸制而成，即蒸饺。<br/>调馅<br/><img alt=\"bf096b63f6246b60317a8cccebf81a4c510fa215.jpg\" src=\"http://7xkon4.com1.z0.glb.clouddn.com/FvyayqKsymJGiJYa2encfT7s-KzH\" title=\"1438652806632564.jpg\"/>先将肉馅煸炒，后用鸡汤或骨汤慢喂，使汤汁浸入馅体，使其膨胀、散落、水灵，增加鲜味。同时，按季节变化和人们口味爱好，配入应时蔬菜制成的菜馅。剂皮制作，也独具一格。用精粉掺入适量熟猪油开水烫拌和制。这样能使剂皮柔软、筋道、透明。老边饺子除蒸煮外，还可烘烤、煎炸。</p>', '100000', 'http://baike.baidu.com/view/101811.htm', null, '2', '1438652824', null, '1', null, '0', '0', '11', 'http://7xkon4.com1.z0.glb.clouddn.com/FgKQ5K5jfKpdoj31Dqk4ARb99EQX');
INSERT INTO `article` VALUES ('1019', '如何发布文章？', '水滴网发布文章方式介绍', '<p>发布文章有三总方式：</p><p><br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;1、注册并通过水滴网“机构认证”功能，开通后您将成为水滴网的“服务机构”，系统将会为您开发发布内容的权限。<br/></p><p><br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;2、非机构用户可以将想发布的内容，通过邮件形式发送到客服邮箱，同时邮件中需要标注您的账号，我们会将原创者一栏中填入您的昵称。</p><p><br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;3、关注我们的微信公众号，在功能号里留言，我们会整合您的留言为您发布文章。</p><p><br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;客服邮箱：sd_net@yeah.net</p><p>&nbsp;&nbsp;&nbsp;&nbsp;<br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;微信二维码：<img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg\" title=\"1439283142671283.jpg\" alt=\"FhpF66Uj6uawCOBWfxid-kVr7nFg.jpg\"/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;<br/></p><p>&nbsp;&nbsp;&nbsp;&nbsp;感谢您对水滴网的照顾，谢谢！<br/></p>', '2', '', null, '2', '1439283195', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/Fg4Qjsz-QBBoxjnQW7N99TSVGipH');
INSERT INTO `article` VALUES ('1020', '什么是服务机构？', '服务机构介绍', '<p>水滴网是由信息做为基础的信息平台，其中一半以上的信息是来源于合作的服务机构，服务机构的信息会更准确，更及时。<br/></p><p><br/></p><p>服务机构可能是某一行业的公司，工作室甚至是个人，水滴网会对服务机构发布的内容进行及时并且严格的审核来确保信息的准确。</p><p><br/></p><p>成为服务机构需要添加一些信息，可在首页中“申请成为服务机构”中来进行填写。</p><p><br/></p><p>如有其它问题可发送邮件到：sd_net@yeah.net</p><p><br/></p><p>微信二维码：<img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg\" title=\"1439283724541585.jpg\" alt=\"FhpF66Uj6uawCOBWfxid-kVr7nFg.jpg\"/></p><p><br/></p><p>感谢您对水滴网的照顾，谢谢！</p>', '2', '', null, '2', '1439283733', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/Fg4Qjsz-QBBoxjnQW7N99TSVGipH');
INSERT INTO `article` VALUES ('1021', '怎么才能添加分类？', '分类不够怎么办~看这里~', '<p>目前水滴网的分类是有系统管理员来设置及修改，如有需求请发送邮件到客服邮箱：<span style=\"color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; background-color: rgb(255, 255, 255);\">sd_net@yeah.net</span></p><p><br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">微信二维码：<img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg\" title=\"1439284751464237.jpg\" alt=\"FhpF66Uj6uawCOBWfxid-kVr7nFg.jpg\"/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\"><br style=\"box-sizing: border-box;\"/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">感谢您对水滴网的照顾，谢谢！</p><p><br/></p>', '2', '', null, '2', '1439284753', null, '1', null, '0', '0', '4', 'http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg');
INSERT INTO `article` VALUES ('1022', '我的文章怎么没了？', '文章没了？不可能~只要你不删，就不能没的', '<p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">文章不显示原因有一下几点：</p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;1.文章正在审核中或未通过审核。<br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;2.文章中出现违规内容，或被举报并核实举报情况而被封杀。<br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;3.文章发布时间过久并无人问津。<br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">&nbsp;&nbsp;&nbsp;&nbsp;4.文章所属分类被系统销毁。<br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\"><br/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">如有其它问题可发送邮件到：sd_net@yeah.net</p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\"><br style=\"box-sizing: border-box;\"/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">微信二维码：<img src=\"http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg\" title=\"1439284726777862.jpg\" alt=\"FhpF66Uj6uawCOBWfxid-kVr7nFg.jpg\"/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\"><br style=\"box-sizing: border-box;\"/></p><p style=\"box-sizing: border-box; margin-top: 1.6rem; margin-bottom: 1.6rem; color: rgb(51, 51, 51); font-family: &#39;Segoe UI&#39;, &#39;Lucida Grande&#39;, Helvetica, Arial, &#39;Microsoft YaHei&#39;, FreeSans, Arimo, &#39;Droid Sans&#39;, &#39;wenquanyi micro hei&#39;, &#39;Hiragino Sans GB&#39;, &#39;Hiragino Sans GB W3&#39;, FontAwesome, sans-serif; line-height: 25.6000003814697px; white-space: normal; background-color: rgb(255, 255, 255);\">感谢您对水滴网的照顾，谢谢！</p><p><br/></p>', '2', '', null, '2', '1439284729', null, '1', null, '0', '0', '6', 'http://7xkon4.com1.z0.glb.clouddn.com/FhpF66Uj6uawCOBWfxid-kVr7nFg');

-- ----------------------------
-- Table structure for `article_category`
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `parent_category_id` int(11) DEFAULT NULL COMMENT '父类id',
  `can_read` tinyint(1) NOT NULL DEFAULT '1' COMMENT '可浏览',
  `can_write` tinyint(1) NOT NULL DEFAULT '1' COMMENT '可发布',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-正常，1-删除',
  `des` varchar(255) NOT NULL COMMENT '分类描述',
  `is_nav` int(11) DEFAULT '0',
  `pannel_id` int(11) DEFAULT '0' COMMENT '首页显示区域id',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=800001 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article_category
-- ----------------------------
INSERT INTO `article_category` VALUES ('1', '公告板块', null, '1', '1', '0', '站内公告', '0', '-1');
INSERT INTO `article_category` VALUES ('2', '首页链接', null, '1', '1', '0', '首页固定链接', '0', '-1');
INSERT INTO `article_category` VALUES ('100000', '吃货天地', null, '1', '1', '0', '专门给吃货的哦~', '2', '0');
INSERT INTO `article_category` VALUES ('200000', '俺家特色', null, '1', '1', '0', '只有当地人才知道的特色~一般人我都不告诉', '3', '0');
INSERT INTO `article_category` VALUES ('300000', '大东北', null, '1', '1', '0', '来玩啊~', '0', '5');
INSERT INTO `article_category` VALUES ('400000', '推荐你来', null, '1', '1', '0', '来了别白来~去看看~', '0', '4');
INSERT INTO `article_category` VALUES ('500000', '广场舞圣地', null, '1', '1', '0', '你是我天边最美的云彩~', '0', '2');
INSERT INTO `article_category` VALUES ('600000', '运动运动', null, '1', '1', '0', '生命在于运动~', '0', '3');
INSERT INTO `article_category` VALUES ('700000', '购物', null, '1', '1', '0', '想购物，就看这里', '1', '0');
INSERT INTO `article_category` VALUES ('800000', '晚上去哪', null, '1', '1', '0', '夜市&夜视', '0', '1');

-- ----------------------------
-- Table structure for `article_comment`
-- ----------------------------
DROP TABLE IF EXISTS `article_comment`;
CREATE TABLE `article_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '评论内容',
  `article_id` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0-正常，-1-屏蔽',
  PRIMARY KEY (`comment_id`),
  KEY `fk_article_id_comment` (`article_id`) USING BTREE,
  KEY `fk_user_id_comment` (`user_id`) USING BTREE,
  CONSTRAINT `article_comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `article_comment_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`article_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article_comment
-- ----------------------------
INSERT INTO `article_comment` VALUES ('1', null, '123请问请问请问', null, '1438229816', '0');
INSERT INTO `article_comment` VALUES ('2', null, '阿萨德', null, '1438229886', '0');
INSERT INTO `article_comment` VALUES ('3', null, '222', null, '1438229922', '0');
INSERT INTO `article_comment` VALUES ('4', null, '55', null, '1438230112', '0');
INSERT INTO `article_comment` VALUES ('5', null, '阿萨德', null, '1438230318', '0');
INSERT INTO `article_comment` VALUES ('6', null, '阿萨德', null, '1438230881', '0');
INSERT INTO `article_comment` VALUES ('7', null, '123', null, '1438230943', '0');
INSERT INTO `article_comment` VALUES ('8', null, '阿阿萨德', null, '1438230954', '0');
INSERT INTO `article_comment` VALUES ('9', null, '阿萨德', null, '1438230961', '0');
INSERT INTO `article_comment` VALUES ('10', null, '请问', null, '1438230967', '0');
INSERT INTO `article_comment` VALUES ('11', null, '123', null, '1438231560', '0');
INSERT INTO `article_comment` VALUES ('12', null, '123', null, '1438232152', '0');
INSERT INTO `article_comment` VALUES ('13', null, '123', null, '1438232497', '0');
INSERT INTO `article_comment` VALUES ('14', null, '123', null, '1438232500', '0');
INSERT INTO `article_comment` VALUES ('15', null, '123 ', null, '1438232502', '0');
INSERT INTO `article_comment` VALUES ('16', null, '123', null, '1438232556', '0');
INSERT INTO `article_comment` VALUES ('17', null, '请问', null, '1438232579', '0');
INSERT INTO `article_comment` VALUES ('18', null, 'asd', null, '1438236068', '0');
INSERT INTO `article_comment` VALUES ('19', '2', '4', '1011', '1439454342', '0');

-- ----------------------------
-- Table structure for `article_read_log`
-- ----------------------------
DROP TABLE IF EXISTS `article_read_log`;
CREATE TABLE `article_read_log` (
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article_read_log
-- ----------------------------
INSERT INTO `article_read_log` VALUES ('2', '1003', '1438334644', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1003', '1438334769', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1003', '1438334940', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1003', '1438335457', '180.153.206.19');
INSERT INTO `article_read_log` VALUES ('0', '1003', '1438347927', '175.167.128.19');
INSERT INTO `article_read_log` VALUES ('0', '1003', '1438347946', '175.167.128.19');
INSERT INTO `article_read_log` VALUES ('0', '1003', '1438347989', '175.167.128.19');
INSERT INTO `article_read_log` VALUES ('0', '1003', '1438348033', '175.167.128.19');
INSERT INTO `article_read_log` VALUES ('2', '1003', '1438395059', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1004', '1438395540', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1005', '1438396139', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1005', '1438396420', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1005', '1438396459', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1005', '1438396493', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1005', '1438396525', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1005', '1438396645', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1006', '1438397930', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1006', '1438398742', '180.153.206.22');
INSERT INTO `article_read_log` VALUES ('0', '1004', '1438537618', '123.125.71.115');
INSERT INTO `article_read_log` VALUES ('0', '1005', '1438537685', '123.125.71.86');
INSERT INTO `article_read_log` VALUES ('0', '1006', '1438537818', '220.181.108.158');
INSERT INTO `article_read_log` VALUES ('2', '1007', '1438571476', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1008', '1438571840', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1009', '1438572198', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1010', '1438572398', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1011', '1438572559', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1438573008', '101.226.65.102');
INSERT INTO `article_read_log` VALUES ('0', '1010', '1438573209', '101.226.33.219');
INSERT INTO `article_read_log` VALUES ('0', '1011', '1438573368', '180.153.161.55');
INSERT INTO `article_read_log` VALUES ('2', '1011', '1438577845', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1012', '1438578561', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1013', '1438578834', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1014', '1438579252', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1015', '1438579528', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1016', '1438579805', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1014', '1438580064', '101.226.33.205');
INSERT INTO `article_read_log` VALUES ('0', '1015', '1438580335', '180.153.206.35');
INSERT INTO `article_read_log` VALUES ('0', '1016', '1438580616', '112.64.235.86');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438583067', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438583091', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438583097', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438583338', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438583349', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438588435', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1008', '1438638613', '220.181.108.105');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1438640613', '123.125.71.109');
INSERT INTO `article_read_log` VALUES ('0', '1011', '1438640613', '220.181.108.89');
INSERT INTO `article_read_log` VALUES ('0', '1012', '1438640679', '123.125.71.18');
INSERT INTO `article_read_log` VALUES ('0', '1010', '1438640679', '123.125.71.70');
INSERT INTO `article_read_log` VALUES ('2', '1018', '1438652826', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438667932', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438668972', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669046', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669056', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669064', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669064', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669064', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669065', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669065', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669066', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669066', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669172', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438669190', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1017', '1438670018', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1004', '1438743034', '180.153.214.176');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1438743444', '180.153.214.181');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438758176', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438758281', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438758415', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438758442', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1017', '1438758446', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438758581', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438759520', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438765427', '101.226.68.217');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438765427', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1438765437', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1017', '1439054410', '220.181.108.108');
INSERT INTO `article_read_log` VALUES ('0', '1013', '1439056410', '220.181.108.107');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1439183509', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1005', '1439183509', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1012', '1439183509', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1008', '1439183509', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1011', '1439183509', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1010', '1439183510', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1004', '1439183510', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1006', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1013', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1018', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1007', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1015', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1016', '1439183513', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1017', '1439183514', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1014', '1439183514', '183.60.243.234');
INSERT INTO `article_read_log` VALUES ('0', '1006', '1439229909', '180.153.6.103');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1439229910', '101.226.64.175');
INSERT INTO `article_read_log` VALUES ('0', '1006', '1439229910', '101.226.89.120');
INSERT INTO `article_read_log` VALUES ('0', '1013', '1439229910', '180.153.81.163');
INSERT INTO `article_read_log` VALUES ('0', '1009', '1439229910', '180.153.201.79');
INSERT INTO `article_read_log` VALUES ('0', '1013', '1439229910', '101.226.65.108');
INSERT INTO `article_read_log` VALUES ('0', '1012', '1439236974', '101.226.64.175');
INSERT INTO `article_read_log` VALUES ('0', '1012', '1439236974', '180.153.212.13');
INSERT INTO `article_read_log` VALUES ('0', '1015', '1439263923', '123.125.71.91');
INSERT INTO `article_read_log` VALUES ('0', '1014', '1439265923', '220.181.108.116');
INSERT INTO `article_read_log` VALUES ('0', '1016', '1439267923', '123.125.71.29');
INSERT INTO `article_read_log` VALUES ('2', '1019', '1439283197', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1020', '1439283734', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1021', '1439283934', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1022', '1439284580', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1022', '1439284710', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1022', '1439284731', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1021', '1439284756', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1019', '1439284970', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1020', '1439284973', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1021', '1439284975', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1022', '1439284977', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('2', '1011', '1439285041', '58.83.244.49');
INSERT INTO `article_read_log` VALUES ('0', '1022', '1439285392', '101.226.33.223');
INSERT INTO `article_read_log` VALUES ('0', '1020', '1439285784', '112.64.235.251');
INSERT INTO `article_read_log` VALUES ('0', '1011', '1439285851', '101.226.65.105');
INSERT INTO `article_read_log` VALUES ('0', '1022', '1439300434', '123.125.71.49');
INSERT INTO `article_read_log` VALUES ('0', '1019', '1439302434', '123.125.71.47');
INSERT INTO `article_read_log` VALUES ('0', '1021', '1439304434', '123.125.71.60');
INSERT INTO `article_read_log` VALUES ('0', '1020', '1439306434', '123.125.71.56');
INSERT INTO `article_read_log` VALUES ('0', '1014', '1439444655', '127.0.0.1');
INSERT INTO `article_read_log` VALUES ('2', '1011', '1439454332', '127.0.0.1');
INSERT INTO `article_read_log` VALUES ('2', '1011', '1439454345', '127.0.0.1');
INSERT INTO `article_read_log` VALUES ('2', '1016', '1439456116', '127.0.0.1');
INSERT INTO `article_read_log` VALUES ('2', '1012', '1439520012', '127.0.0.1');
INSERT INTO `article_read_log` VALUES ('0', '1019', '1439603200', '127.0.0.1');

-- ----------------------------
-- Table structure for `fankui`
-- ----------------------------
DROP TABLE IF EXISTS `fankui`;
CREATE TABLE `fankui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fankui
-- ----------------------------
INSERT INTO `fankui` VALUES ('1', 'qwe', '1440661351', '0');
INSERT INTO `fankui` VALUES ('2', '123', '1440661371', '2');
INSERT INTO `fankui` VALUES ('3', '123', '1440661437', '2');
INSERT INTO `fankui` VALUES ('4', 'qq', '1440661443', '2');
INSERT INTO `fankui` VALUES ('5', '333', '1440661445', '2');
INSERT INTO `fankui` VALUES ('6', '2222', '1440661447', '2');
INSERT INTO `fankui` VALUES ('7', '123123', '1440661448', '2');

-- ----------------------------
-- Table structure for `login_log`
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL COMMENT '登陆时间',
  `ip` varchar(255) DEFAULT NULL COMMENT '登陆ip',
  `json` text COMMENT '其他预留信息',
  PRIMARY KEY (`login_id`),
  KEY `fk_user_id_login_log` (`user_id`) USING BTREE,
  CONSTRAINT `login_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of login_log
-- ----------------------------
INSERT INTO `login_log` VALUES ('8', '2', '1438333960', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('9', '2', '1438335071', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('10', '2', '1438395052', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('11', '2', '1438571222', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('12', '3', '1438829769', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('13', '2', '1438930946', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('14', '2', '1439282332', '58.83.244.49', null);
INSERT INTO `login_log` VALUES ('15', '2', '1439444749', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('16', '2', '1439446229', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('17', '2', '1439520003', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('18', '2', '1439615273', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('19', '2', '1439797505', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('20', '2', '1439867212', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('21', '104', '1439867619', '127.0.0.1', null);
INSERT INTO `login_log` VALUES ('22', '2', '1440661367', '127.0.0.1', null);

-- ----------------------------
-- Table structure for `org`
-- ----------------------------
DROP TABLE IF EXISTS `org`;
CREATE TABLE `org` (
  `org_id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user_id` int(11) NOT NULL COMMENT '创建者user_id',
  `create_time` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '机构名称',
  `des` varchar(255) DEFAULT NULL COMMENT '机构简介',
  `main_http_url` varchar(255) DEFAULT NULL COMMENT '主站地址',
  `logo_url` varchar(255) DEFAULT NULL COMMENT 'logo地址',
  `sh_file_url` varchar(255) DEFAULT NULL COMMENT '审核文件地址',
  `sh_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1-正常机构 100-系统管理机构',
  `status` tinyint(1) DEFAULT '0' COMMENT '0-待审核，1-审核通过，-1-禁用',
  PRIMARY KEY (`org_id`),
  KEY `fk_user_id_org` (`create_user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of org
-- ----------------------------
INSERT INTO `org` VALUES ('1', '2', '0', '系统管理员组', '系统管理员组', null, null, null, null, '100', '1');
INSERT INTO `org` VALUES ('2', '3', '1439449900', 'xx服务机构', 'xx服务机构', '', null, null, null, '1', '1');

-- ----------------------------
-- Table structure for `qixi`
-- ----------------------------
DROP TABLE IF EXISTS `qixi`;
CREATE TABLE `qixi` (
  `q_id` int(11) NOT NULL AUTO_INCREMENT,
  `boy` varchar(255) DEFAULT NULL,
  `girl` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  `ok` tinyint(1) DEFAULT '0' COMMENT '0-未ok  1-成了',
  `create_time` int(11) DEFAULT NULL,
  `ok_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`q_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of qixi
-- ----------------------------
INSERT INTO `qixi` VALUES ('1', '', '', '', null, '0', '1440051404', null);
INSERT INTO `qixi` VALUES ('2', '', '', '', null, '0', '1440051508', null);
INSERT INTO `qixi` VALUES ('3', '123', 'qwe', 'asd', null, '0', '1440051579', null);
INSERT INTO `qixi` VALUES ('4', 'q', 'w', 'e', '3', '1', '1440051654', null);
INSERT INTO `qixi` VALUES ('5', '水滴', '用户', '刚写您对水滴的支持，我们做不到最好，但是我们会做的最贴心', '0', '0', '1440052868', null);

-- ----------------------------
-- Table structure for `sns`
-- ----------------------------
DROP TABLE IF EXISTS `sns`;
CREATE TABLE `sns` (
  `sns_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `create_time` int(11) DEFAULT NULL,
  `type` enum('text') DEFAULT 'text',
  `zan_count` int(11) DEFAULT '0' COMMENT '点赞数量',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '0-正常，1-删除',
  PRIMARY KEY (`sns_id`),
  KEY `fk_user_id_sns` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sns
-- ----------------------------
INSERT INTO `sns` VALUES ('1', '2', '123', '1439616165', 'text', '0', '0');
INSERT INTO `sns` VALUES ('2', '2', '请问请问请问器11111111', '1439616347', 'text', '0', '0');
INSERT INTO `sns` VALUES ('3', '2', '请问请问请问器11111111', '1439616364', 'text', '0', '0');
INSERT INTO `sns` VALUES ('4', '2', '请问请问请问器11111111', '1439616414', 'text', '0', '0');
INSERT INTO `sns` VALUES ('5', '3', '请问请问请问器11111111', '1439616529', 'text', '0', '0');
INSERT INTO `sns` VALUES ('6', '2', '请问请问请问器11111111', '1439616600', 'text', '0', '0');
INSERT INTO `sns` VALUES ('7', '2', '请问请问请问器11111111', '1439616640', 'text', '0', '0');
INSERT INTO `sns` VALUES ('8', '2', '请问请问请问器11111111', '1439616668', 'text', '0', '0');
INSERT INTO `sns` VALUES ('9', '2', '请问请问请问器11111111', '1439616680', 'text', '0', '0');
INSERT INTO `sns` VALUES ('10', '2', '请问请问请问器11111111', '1439616690', 'text', '0', '0');
INSERT INTO `sns` VALUES ('11', '2', '请问请问请问器11111111', '1439616741', 'text', '0', '0');
INSERT INTO `sns` VALUES ('12', '2', '请问请问请问器111111111', '1439616817', 'text', '0', '0');
INSERT INTO `sns` VALUES ('13', '2', '请问请问请问器111111111123123', '1439616821', 'text', '0', '0');
INSERT INTO `sns` VALUES ('14', '2', '请问请问请问器阿萨德', '1439616824', 'text', '0', '0');
INSERT INTO `sns` VALUES ('15', '2', '请问请问请问器阿萨123德', '1439616952', 'text', '0', '0');
INSERT INTO `sns` VALUES ('16', '2', '请问', '1439616966', 'text', '0', '0');
INSERT INTO `sns` VALUES ('17', '2', '123', '1439617070', 'text', '0', '0');
INSERT INTO `sns` VALUES ('18', '2', '123去', '1439617075', 'text', '0', '0');
INSERT INTO `sns` VALUES ('19', '2', '123去', '1439617141', 'text', '0', '0');
INSERT INTO `sns` VALUES ('20', '2', '123去', '1439617144', 'text', '0', '0');
INSERT INTO `sns` VALUES ('21', '2', '123去', '1439617145', 'text', '0', '0');
INSERT INTO `sns` VALUES ('22', '2', '123去', '1439617147', 'text', '0', '0');
INSERT INTO `sns` VALUES ('23', '2', '123去', '1439617148', 'text', '0', '0');
INSERT INTO `sns` VALUES ('24', '2', '123去', '1439617154', 'text', '0', '0');
INSERT INTO `sns` VALUES ('25', '2', '123去', '1439617156', 'text', '0', '0');
INSERT INTO `sns` VALUES ('26', '2', '123去', '1439617160', 'text', '0', '0');
INSERT INTO `sns` VALUES ('27', '2', '123去', '1439617163', 'text', '0', '0');
INSERT INTO `sns` VALUES ('28', '2', '123去请问', '1439617167', 'text', '0', '0');
INSERT INTO `sns` VALUES ('29', '2', '请问', '1439617305', 'text', '0', '0');
INSERT INTO `sns` VALUES ('30', '2', '玩儿', '1439617348', 'text', '0', '0');
INSERT INTO `sns` VALUES ('31', '2', '请问', '1439773352', 'text', '0', '0');
INSERT INTO `sns` VALUES ('32', '2', '这是什么', '1439773360', 'text', '0', '0');
INSERT INTO `sns` VALUES ('33', '2', '怎么回事', '1439773366', 'text', '0', '0');
INSERT INTO `sns` VALUES ('34', '2', '啊军思考的骄傲卡死机读卡加手机的空间阿克苏进口大家思考进度款拉升kd\n\nasd \n阿萨德\n阿萨德', '1439773376', 'text', '0', '0');
INSERT INTO `sns` VALUES ('35', '2', '1', '1439773391', 'text', '0', '0');
INSERT INTO `sns` VALUES ('36', '2', '3', '1439773394', 'text', '0', '0');
INSERT INTO `sns` VALUES ('37', '2', '4', '1439773397', 'text', '0', '0');
INSERT INTO `sns` VALUES ('38', '2', '5', '1439773400', 'text', '0', '0');
INSERT INTO `sns` VALUES ('39', '2', '1', '1439773402', 'text', '0', '0');
INSERT INTO `sns` VALUES ('40', '2', '1', '1439774373', 'text', '0', '0');
INSERT INTO `sns` VALUES ('41', '2', '44', '1439775631', 'text', '0', '0');
INSERT INTO `sns` VALUES ('42', '2', '112233', '1439797511', 'text', '0', '0');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `realname` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `head_pic_url` varchar(255) DEFAULT NULL COMMENT '头像地址',
  `des` varchar(255) DEFAULT NULL COMMENT '个人简介',
  `sex` tinyint(2) DEFAULT '0' COMMENT '0-保密，1-男，2-女',
  `phone` varchar(255) DEFAULT NULL COMMENT '电话',
  `qq` varchar(255) DEFAULT NULL COMMENT 'qq号',
  `status` tinyint(1) DEFAULT '0' COMMENT '0-正常，-1-禁用，1-认证通过,2-社工身份,99-系统管理员',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `org_id` int(11) DEFAULT NULL COMMENT '机构id',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', 'lvxin1', 'lvxin0315@163.com', '8c8d1d9108c5aefde0acc80ce5283a6d', '吕鑫1', 'http://7xkon4.com1.z0.glb.clouddn.com/FoeLwE5g1yNk7DZWyUOJZbW0Db38', 'hi', '1', '18624088832', '334808108', '99', '1438333937', '1');
INSERT INTO `user` VALUES ('3', '我是Q', '334808108@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '哈哈哈哈哈哈哈~', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('4', 'test0', '89803050@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第0个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('5', 'test1', '31743701@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第1个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('6', 'test2', '62996412@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第2个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('7', 'test3', '57042103@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第3个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('8', 'test4', '67743594@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第4个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('9', 'test5', '77262365@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第5个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('10', 'test6', '74530706@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第6个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('11', 'test7', '75390627@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第7个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('12', 'test8', '95510528@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第8个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('13', 'test9', '42746319@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第9个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('14', 'test10', '733913810@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第10个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('15', 'test11', '456488711@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第11个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('16', 'test12', '369927212@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第12个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('17', 'test13', '254177513@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第13个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('18', 'test14', '790228914@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第14个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('19', 'test15', '428168315@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第15个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('20', 'test16', '671902116@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第16个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('21', 'test17', '780544717@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第17个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('22', 'test18', '664252318@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第18个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('23', 'test19', '974500819@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第19个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('24', 'test20', '499918620@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第20个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('25', 'test21', '312120221@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第21个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('26', 'test22', '550428522@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第22个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('27', 'test23', '178819423@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第23个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('28', 'test24', '715087824@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第24个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('29', 'test25', '265570725@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第25个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('30', 'test26', '198757526@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第26个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('31', 'test27', '124457427@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第27个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('32', 'test28', '845187728@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第28个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('33', 'test29', '724229529@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第29个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('34', 'test30', '692572630@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第30个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('35', 'test31', '672526031@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第31个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('36', 'test32', '862440332@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第32个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('37', 'test33', '438096733@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第33个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('38', 'test34', '892985034@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第34个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('39', 'test35', '350802935@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第35个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('40', 'test36', '294623436@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第36个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('41', 'test37', '379394537@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第37个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('42', 'test38', '994439038@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第38个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('43', 'test39', '798177039@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第39个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('44', 'test40', '891737140@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第40个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('45', 'test41', '187011741@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第41个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('46', 'test42', '746934642@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第42个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('47', 'test43', '542426243@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第43个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('48', 'test44', '403781444@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第44个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('49', 'test45', '833170545@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第45个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('50', 'test46', '344916446@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第46个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('51', 'test47', '333550347@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第47个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('52', 'test48', '580756248@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第48个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('53', 'test49', '178982149@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第49个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('54', 'test50', '427273250@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第50个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('55', 'test51', '477105051@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第51个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('56', 'test52', '950439452@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第52个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('57', 'test53', '974446653@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第53个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('58', 'test54', '299560554@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第54个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('59', 'test55', '834201355@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第55个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('60', 'test56', '596164256@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第56个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('61', 'test57', '191786057@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第57个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('62', 'test58', '600667358@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第58个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('63', 'test59', '821506059@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第59个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('64', 'test60', '823486360@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第60个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('65', 'test61', '581000461@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第61个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('66', 'test62', '636149062@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第62个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('67', 'test63', '300130163@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第63个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('68', 'test64', '715738964@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第64个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('69', 'test65', '892089865@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第65个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('70', 'test66', '156005866@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第66个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('71', 'test67', '464518267@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第67个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('72', 'test68', '689588768@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第68个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('73', 'test69', '319498669@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第69个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('74', 'test70', '243570970@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第70个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('75', 'test71', '286892371@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第71个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('76', 'test72', '717258072@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第72个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('77', 'test73', '279893673@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第73个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('78', 'test74', '648844374@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第74个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('79', 'test75', '961697075@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第75个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('80', 'test76', '326524576@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第76个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('81', 'test77', '856608077@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第77个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('82', 'test78', '677381778@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第78个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('83', 'test79', '572265679@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第79个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('84', 'test80', '378499380@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第80个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('85', 'test81', '799641981@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第81个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('86', 'test82', '968071882@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第82个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('87', 'test83', '313042583@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第83个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('88', 'test84', '400960284@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第84个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('89', 'test85', '192328585@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第85个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('90', 'test86', '826470286@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第86个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('91', 'test87', '934027787@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第87个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('92', 'test88', '366129588@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第88个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('93', 'test89', '451334689@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第89个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('94', 'test90', '891465990@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第90个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('95', 'test91', '962999191@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第91个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('96', 'test92', '690673892@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第92个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('97', 'test93', '771104593@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第93个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('98', 'test94', '468614394@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第94个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('99', 'test95', '261067695@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第95个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('100', 'test96', '457926496@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第96个测试用户', '0', null, null, '1', '1438829760', null);
INSERT INTO `user` VALUES ('101', 'test97', '790527397@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第97个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('102', 'test98', '196804498@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf1', '这是第98个测试用户', '0', null, null, '2', '1438829760', null);
INSERT INTO `user` VALUES ('103', 'test99', '911566899@qq.com', '8c8d1d9108c5aefde0acc80ce5283a6d', null, 'http://7xkon4.com1.z0.glb.clouddn.com/Fvom7l-8DBWtPwjrHhdzgm4IGmKf', '这是第99个测试用户', '0', null, null, '0', '1438829760', null);
INSERT INTO `user` VALUES ('104', 'ceshi', '15942350040@qq.com', '0f9e745bc146736215ef8d903131bcdc', null, 'http://7xkon4.com1.z0.glb.clouddn.com/FjGMNBfV78qo8VLHeWaRigxhJQZ7', 'qwe', '0', '15942350050', null, '0', '1439865303', null);
INSERT INTO `user` VALUES ('105', '111111', '18624088831@qq.com', '0f9e745bc146736215ef8d903131bcdc', null, 'http://7xkon4.com1.z0.glb.clouddn.com/FjGMNBfV78qo8VLHeWaRigxhJQZ7', '1111', '1', '18624088831', null, '0', '1439865408', null);
INSERT INTO `user` VALUES ('106', '1122233', '123@qwe.com', '0f9e745bc146736215ef8d903131bcdc', null, null, '请问', '0', '15942350080', null, '0', '1439965308', null);

-- ----------------------------
-- Table structure for `user_org_link`
-- ----------------------------
DROP TABLE IF EXISTS `user_org_link`;
CREATE TABLE `user_org_link` (
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `org_id` int(11) NOT NULL COMMENT '机构id',
  `create_time` int(11) DEFAULT NULL COMMENT '关联时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_org_link
-- ----------------------------
INSERT INTO `user_org_link` VALUES ('2', '1', null);

-- ----------------------------
-- Table structure for `weixin_log`
-- ----------------------------
DROP TABLE IF EXISTS `weixin_log`;
CREATE TABLE `weixin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ToUserName` varchar(255) DEFAULT NULL COMMENT '公众号原始id',
  `FromUserName` varchar(255) DEFAULT NULL COMMENT '关注用户openid',
  `CreateTime` int(11) DEFAULT NULL,
  `MsgType` varchar(255) DEFAULT NULL COMMENT '信息类型',
  `Content` varchar(255) DEFAULT NULL COMMENT '文本消息内容',
  `PicUrl` varchar(255) DEFAULT NULL COMMENT '图片消息图片url',
  `MediaId` varchar(255) DEFAULT NULL COMMENT '媒体消息id',
  `MsgId` varchar(255) DEFAULT NULL COMMENT '消息id',
  `Url` varchar(255) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Description` text,
  `Recognition` text COMMENT '语音识别文字内容',
  `Event` varchar(255) DEFAULT NULL,
  `EventKey` varchar(255) DEFAULT NULL,
  `Ticket` varchar(255) DEFAULT NULL COMMENT '二维码参数',
  `Encrypt` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of weixin_log
-- ----------------------------
INSERT INTO `weixin_log` VALUES ('1', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438754503', 'text', '阿萨德', null, null, '6179403537566236916', null, null, null, null, null, null, null, '8RC5yDN622Rn6LXrWHOLmwC6Oc7VLaeVBsQaP/fv8epinnytmsI7zYFBJZOQPJ5ysV8xyG7xYceObP4ARQYOHNo8TS19u1jfrsfIoS2TVSPrYojDyeGmeUlguDl4LaiX27kAVNAC5/Be0sdf2PcoRXW3Iaw52kZOLwo8/sHAMfUg0Of5yfApVxvJF4+2cJ4uvata78EbBJd8l8gb3O1lKy6LPNCPFacBQ8xSQVphpYyvMXQ/eZ26/Iy96/81oBbFEdbR/YV4YtY1zSMtC/h+c1VElp7Hcx4UGnyWFbE6zTWM5eUp+GhcbSgE7TYAHRCxlkqSoBOG9I1j+lEMYo86wuLoKQ+NGBsz7nqg2g+gfRQyWAbmwhVWEb3nVWuAzC8gA2ac53d6M0ziXHOVAlrUy9hiSePNQbibgIReOS3tXE7rtSKrVKW2DMkomWev+3Krs8JOZEV+d5Og6VcfACPcbg==');
INSERT INTO `weixin_log` VALUES ('2', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438754510', 'image', null, 'http://mmbiz.qpic.cn/mmbiz/3NCorFEXSe0K6ns80EzOP8gk723xeltjOtvMH6mNZRo4IMLgZEtgPBiaTEiaav2gBFugHkR2r1hu8o6HdQxmiaAtQ/0', 'RxIYXKg1nEqhGVEZv5XquE3a5GKXE3igVzwGu6bQyMP4xa4Ec_n7WUuNCY2AlZdr', '6179403567631007995', null, null, null, null, null, null, null, 'TvcCwqfqHlznNtlEjP0urrjlDVr/n+RDMth0fuuq8FmEBPpI3DmmnwAVb9ztVswJoNNDy1ETeX1yAX6L/Bjk58rAdC/dhG3HFZpRNsraHfidtSVbB2TxVfTOT2fDlQFxM/C9yNIMTjgt4yDzF3tERO2yVnHrK4Rk0wxy3LIJyD62YtLI0PZzJBYR2MqLxGHbJijlefsSXs2okRh7lQyJRnpqxy8PGZpfZ2v3APpr2wwrXh2lYbIyHKX+IO7zIj55O+0aEoSx0VQresywDLHwUuK+hpclxwIHEtqVKTd80p3eWPvKqZgxNiuRP5qT0p3hhMRRnX4TbPZabPzSS0K7Bi7Tvtgg+QxDfAe99WzOV0PTrMHZVnyLSYO+/9onXKiij7tMu1ub+X15gp/TiTaIbEZ3aCoVS8TkPYn/R36kmEq+GX4yY71NVUJltLuNZ2K0UDo1WuSxjB3zSEyKuy00hPZf0j3oLKLrje+vIutn+plj8Fg+TDeDMTEDLg6eu6mdcgeywRnOlsWX1Jjn3mQO8FoR68KBDK8dYcLIbLX3HquAirBxYGwY9DnG3RhAwTgJrI5dRHWnGHYWpwpF/1A/Jj2ZQgGV26AVUO7xJoYhN3c7rAWIs9EmD7+Ge9H8pN27vaCZb2ImH2j678wkyIcTMk6lejjGwQifhdTCGnR8L7b5lamWk4gc+it9MVJ4TU8HIimIBk5l4KvwxxelpWggEw==');
INSERT INTO `weixin_log` VALUES ('3', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438754542', 'text', '/:X-)', null, null, '6179403705069961475', null, null, null, null, null, null, null, 'iXZ/QDBdkSciWFQk+I/7f/VPDHmerVBXzokrbG1NxbW28jjM+AbmFWTdK9gWMTmj3U71O8Y3YmG6M1Y2jNc32+AqIwBKoEKlGBm2z3XEIqUgxuqn32W1TN5UH6Zjnk1rARBfZjD8NsqvjTNwAczut6hQX20JTU3b/ausFzvSaIuTUmNra/bbi4NzcYn7LXUruiFyrkEUZ3hqwyft7Y1gj49F2+aMXRGejTt2jHbc4NHCCGM39hYSOT/nhqap+OwedMD7iyQ1Yl7/huRDs5Gh8FuvwJkJU56sTsPPLULvwdwB8pcz3tnN62qVY4YDDNJSALvJjmA6R50BE1xkRyl+EDFyXlB3kqmFbeVNgEx24eqvKdH1Sf9H89cdmbH4CbncqqBZ3FHhbVzrMvZbGD6EiM7FHxjHbU/bLd0SCJF8bhk=');
INSERT INTO `weixin_log` VALUES ('4', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438754737', 'text', '/:--b抱歉~该类型消息还在开发中~敬请期待~/:B-)', null, null, '6179404542588584288', null, null, null, null, null, null, null, 'Aaivl3YdRdFmbpLQLrS4TQTKWi0hkloOLSD1MYhFrGgYFHZ6LpbpfE+jeddg73tGo5Sd9BqRTUU74xX1Y5uIALrM062LGfNw0FRmyLAGtnTY2AsThBZcb1MNcMjERaRNRukIT56AGv0EUVjN5hDLtwMD8fNEkNIGgsf//ea0reXr49rsk6sDsD0tUQb41V9a6PvXv42MUrZYIBIMhcSQCuqvZqc144giy0EcsOxVgnKJit30jTM8OkaJ9I2Dkjm/S/k0XHFRcPNeLYSbk7uGg1ordI1z1vGpchz4XyW4po+FKnxaXtEg9aTH20gJIJVAPki4Ljf9vkdRzs33mKCsnqriisvbBAxxjTEuCV61q2SIA8OpFdq16f8THUiRMWtaCHv5YWNpuz9Fi6Tn+q6psia+NCdMUwHHG2CGWPLKkVkdWtIdACZ67dvYwAy2nJZyn9EpdHQVIKfmw5rSvr+R3ghZs8meGgL3M/A088UzVusy0wjhiFCrOWx5Cn/n3UcZ');
INSERT INTO `weixin_log` VALUES ('5', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438754807', 'text', '阿萨德', null, null, '6179404843236295036', null, null, null, null, null, null, null, 'atjwa/Qs+D/DUTxZFi0lWHXIIoz7hENRMrEYgHnvvSKXZJWldVJr7l+B4Q14x4wGKG6i6IxmvCFin1LnRyFmT53xFNHZgVyohRcoL4STnNpGS9Jm3uEaLIzuANuN+Tcq8lTGpynEAOMuMOos3QE8aEKKe50lDYQqREG/zB6HxjudlPBcDwWaV+UX2lWazWG5NF9P7RMWXrBmblv2tnwsmlDlxUqyvwM4wIkR76/aXd0qu3XB78ttSKhbBiGI5tgTD+bKVYBReILb058oJnm/o7ET0TRPX7VxyaaqvqYFnOEUrTC9nHRjsI07BYfxyTZDx4VKeUXpQCgY2X1hgFQPlN1Kf2dH+1xZBFKhhL0UhfLDPxrkCuFEOkjPTyPTlsZenGz079mgq25PKgN+EQNXl3q3mUHFmdLNOjEknPX/MFjTbB+gMqpshKtxZIF4w+QJZiCOcFMXwgjcVE/68EDcww==');
INSERT INTO `weixin_log` VALUES ('6', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756057', 'text', '感谢您的关注/:,@-D，如有意见可直接发送消息，我们会尽快完善。\n回复 @+关键字 如：\"@中街\" 即可查询到相关信息', null, null, '6179410211945415584', null, null, null, null, null, null, null, 'MgM1PMdLUFIP44GRibRyg0ew32x9AonceldLxhRwgMFJjlrT8GPunqMfGoY35cnx3uUnvyTtpy5vz8xj5uzncpjpq43NmsxFkuhT8sD2nXQkCuoByY0JG6a+s6aHKUjDH1bzGhqrzRyDNBacd3hx4HV/51yhfc8sNroEMyrqJKdSkwf1Eg6tExQsxOgwXqyJfwlGw2hgdWoFyE0T1qoH1ogi76gXhP8jogKUuMNKNTyljXPDymWtj0st+qbSGluUblq37FWllNuAnXq9OEtuY4WyCxjIZcrucLN60yeLDqYu4GnpW7kDFFG3/GkLhoJDfsm02Pgi+nDA2Rmm0K+2blwigt00Gi7hG6tH7LkFH6nCCzgaSOw16S/eUw1d1nR1S9AIEGsdX9MFp3kFVg1WW489iPiQ6scxvhKdHYQEk34V8/8Guzbs3JMHsjNjypb5sBK+0S2T7v8ffVoRSEnOzLEF6XNLhkQXcquOgVe2mD5fbTONTbujr0htIzhjdiQRplbOH15Baz7G1df8iZbU3uyNJa4vrjM5uYeCyFvmc0GUGHTKRlHhGHKoSWi/HpjSHUglkZKBOjathenwwP6SWnDi3Etbc15WGnWGkrZczlDN/t5lgZyr01uSmltNuuTu');
INSERT INTO `weixin_log` VALUES ('7', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756164', 'event', null, null, null, null, null, null, null, null, null, null, null, 'BB5oIMnD+hPu5tdXFemHJ/CaEYzBZK9ekUEhd7E1zubVfG3SWbZkEEJ0dT7zjU9Xl0j0lusVJHl+vcoMZ/uHvAvD+8TQsIZrc0nnMDv3/Qwvaa4lZOTDSlIlqo+UPXCdSYJOpZYs/0xrcV9cDhSO6O/J5U8oXSI2CcW7mcL8UavFIIyiZFGdFb/UMz83Vy0v2NCSVICDyAgVhDjX03OBIfNAj6BFP9Hvx5jPFgXYP8CBULyTXCKdPSCT01RLHsDKJyN3bwtVDJwbdX7rAtXf/AUnE6d/9rWN7Q4uy2EpvbGomMriAUik60IkcaQHDvUe1UPD4PfsIfYRO4IeWenvqSfoDKwq2Q8twMQafwBRvvY4WfTi8AtpKza486BvBANcHMh0C/mw2PuGvzNapSo8jADDCjpC/nZ/ehtaw3aFHhw=');
INSERT INTO `weixin_log` VALUES ('8', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756168', 'event', null, null, null, null, null, null, null, null, null, null, null, '2k50ZovKYcbd+C8BvSrw2MlVeoAEvVY9E2/Jh/6eVdWuPDOPCaYk6QWjKop4JJbyvDErTQpHdIOCFTCPA9R4nMGiPphZNt+2txyGBm9IvhTSqQd5E9NSneql3QCDDSYJ9AxMRx3wXSIPLjDW+fHwQddFfaCgjjFlQh5UYZq5rIBac6N1+zvBTgbUtICq8/94GE0GurThDzZSH7RvUR5n5MwhbvJR/6KX4/Xl61zJpZ5dgk6PQyxgD++SxhpVZMzMu+jpDizAKOM5Ml8LHzx/0Fjxx/Q7WYoct6gNipAF7kVM2Rv75/2tLnIXG0tDgumoYQcGuN01IvsxEmZqwUPluAmtsVJ6izLyjV8gMUt+WeYJcavE2JgfoZtkWSyokSPAczOnvtPSxp41Dx3J3BR+ANUQCdjfZpSWCIx4eu8eVCc=');
INSERT INTO `weixin_log` VALUES ('9', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756206', 'text', '@我', null, null, '6179410851895542787', null, null, null, null, null, null, null, 'DgfYRqRCR/OudOrLnKj6p4B+djY7G7iJYQMNKVfGqImHoJQnItqnh7q6Ws0GfbX7lsEtISpWtIYVJQU1Y1wSVp4sBEW91uzm6v3To7hssXzObLqp0XEY6p1Oc0QYB8kadVEdj5QYcqJYQLbjQ9fquWMIy4MjpvMT5rjjwupM+32AHS5XuOElVQ2it+vYipV/fH+PqOTLjwCtGnpuo9QVjnAZTewfB1veCcxywd026xVexHNgJLmntdX+1PJdSeSSMTxAZMJmyxZmS/bW2zfeJ7l+cHRmOQT5Dpss0ahFwu2m7ipWp58VfoNx7quyeI3BPX5+S3M83rpjOgJwZvjOGCDE1YEtb/njPhFgN3gcuERFSvN7J5gsn+t7bm+HxDGZLbVzNkZpKqvsWJeVCNvAcHoSuq09ePRsOKCsbI3+EnE=');
INSERT INTO `weixin_log` VALUES ('10', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756619', 'text', '您发的我已收到~我们会尽快处理/:,@f', null, null, '6179412625717036283', null, null, null, null, null, null, null, '87KoHFYLCggvCG3NLavjBeMoy43GrQHJ0tofG42CgchTS6BmZtFT+ynwNY6NBfUTuKWxrrHSt+prswkDgR5zd4r9B7bUY+a9wRNMiWH4VYYn/5GBmtyjPS6RAbWGS0EVC78HXYILzCgQOunUB97jyQ0YluzCtxxskZQrZytoay590nDzx6H7Rfkqq6lIg5pPQ8e+L9h2BOxCuOuj8j3YooSevtvwdqSMIQd4S2QFCdDg3GpeUm4pAJFZwCZcJGODAO+yPcXjKkO1Oe2u9KJlkfUe29oQSwrqwjL5dp/VkMjDxmqWpafl9ZvxSjt5t09CNcJ5VmfoPlnwfaGfzDeog6EKGB31npbSdp6U5DVBcvDrmzvIXdY0jAwXJHCRynNbc1V1Qfgkm6uMtrFYImkV5ytq+oe679WqKhz3eXEAS2dWbSf5S5ayMceIB+2NEuIAMLK/OyqW+3xjwBOB98aBRJ295buBqkqS4/UlTgxsgMTURUo3tYnJaS50EMY4AtXv');
INSERT INTO `weixin_log` VALUES ('11', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438756959', 'text', '感谢您的反馈~“水滴”会继续完善的~/::*', null, null, '6179414086005917112', null, null, null, null, null, null, null, 'B4w+Lgc1bzoVWdLS16ygWa5U4W3GK41jHmSSrs0TYk7yJSislBMs7sOmRhlpH1KThV0MP0KWIkBDQvupWD5qKEqAe+II6jQOQdMgpQvg59294GvT1Dz2Nfsy+MggTUYRk8VjsLMfYrNEW1DaIXdIXC/cT7ngXRwcf/J/sWsB8M1euIa6nIIDklYy+/+GvyLI+b/jH5V0XuYy+Lb5Iz9tijQebxHLOl/6j+Le4mgNDq715Z6f9z9Ct+twmLjiT4qhhGyapuNYkEhQnCmd1S4YvzHyvSQi+belObAu5i2OcJm6JLRfkaHI8BZOsChEKwKBu5RuKy9ePAMAEKC3SZi761Y3QBlO155VK8oY0kotmg5p4m1Ecv+sQVaDMA+xl55KrNz32IMaWZWTxK2piuGwsljNqO5nutTIlQf/Ljg1iN4yJXQzB/Df7EYE/9lHdL05f3xfJgikt3i9LSWHLO4qnCduIOyqup9S3WQj/8ihyBS2j7lFkU/lyWE359YNJnUN');
INSERT INTO `weixin_log` VALUES ('12', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438757456', 'text', '抱歉~还没有您需要内容/::P', null, null, '6179416220604663588', null, null, null, null, null, null, null, 'EKdmQVhNCLPznUz4r2nSM4Xq3QBibQj0ZviunFLgLBtO23AZdvMfssNhEPOcRpPENZBCS/sZ6A2escOSn5rGPe2PaIgiinrD3ME24PLNjYnW2vCOtE6Co5XqjmwtJ96lqj8i02c8xhO0oo68+iaVP+8B3guzl0gWsMyyjnNtGIMjWIjihXWIqC3jikMgvl93JPWfYxijO5kLtEsCXV5jSb4iVgamOIbmvwsUPw716mkIM2jSZh8e7pvvTKOkLcBbxITSyx1og2wjV5Nu1wdYqbpZUebpfiQ/z1Ayoa8GOl8AbtKU3pUe52lKPlsCQfbYkKqhYndzJDpj6kORqTbocG1EUimjJsoAwB9s7r/A5rdZ0uEQPo/nHXlaMO929GhjDKKK9MqDCc6iXE0ED78BGO+XuQ61c6+hK3GIsHBMYm7xekuaxVT9Y8NeJPDJHWDG9t4KqLc7PxhGDmQgk2TDvw==');
INSERT INTO `weixin_log` VALUES ('13', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438758141', 'text', '123', null, null, '6179419162657261872', null, null, null, null, null, null, null, 'iQC7d0L0QaBZs8tlDOFzIOV0cg5zc5ZCIjKv36F0ES/OOLLOAV5aX9L/uMd8B+3iqnbaw+E0nIOXa/L+P8rzD74I0hZj+QskVGf62UvfEsDDAzxoa57gt5aRw/FxM9cPOsyGTdsrlQXn19mL05LufAgYCvHzmyR0g1Z7UIyh5pz9ie6VnJzXBWcu0YzKa/49bdPyVQ7Ysu3X5ZLAM+yw7QDpAP+YPh7JhpordDlYZaky2iYRq5IT2dbMLljIyPID21hXP19DLCUEG0I68x9LODCWOpEYrQSRSO8WH8FOcw1R4Y13eKh1xCKLOjiMr+TdfMYYwsQ+b3ba2MkPrAMyfNrulI7U4Tk91kkJpKepeQhUrCU3IfxYVdurGf+W0IkDRgLRZd3K4BYzygJWL0pISrzuPhp5xHgd74ToY/ynGys=');
INSERT INTO `weixin_log` VALUES ('14', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438758144', 'text', '请问', null, null, '6179419175542163768', null, null, null, null, null, null, null, 'kkQuk+jG3CZxh4WVtWPRsw4DRRAmOHV1EaxWZf15NwPgOURxuoBmld9i6AvGbFzOceVG70dlOyLHQm1fn/SbOjRYJzpiGsOaxwiltptzg7JBONx6jDS4nUEtR+bdjGDY6pWD5L8zl/2JK3j8Y7sq5tkBcGQ7ZJzDtAIvYCnff5Z2VvI4RUzbGmRBg34FUjSiZsQ9QCaKZQDFJcKjBbCZ/LGqdyZuZgNXMSfiBL+6gOwa7Ib5pD44q16U/BXsGlrpBq9M6LonNJbyY7GvtF23E7kIheHHy7CkGBEzfQ1rD6ZPiOsydB9aTYebuB/sjZ+WYfuvRzbAmURlmPpxmvEWt/JI+bA5uvwpm+uenQKXZ8+urVg3+SMI9GOEXs3mx620UT1wCmkrXXdlDGKNI3mU57VyTsc3dbweq6UVnio7DNk=');
INSERT INTO `weixin_log` VALUES ('15', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438758154', 'text', '@沈阳', null, null, '6179419218491836743', null, null, null, null, null, null, null, 'exxXN6mtg9/zstAxNnB/6hQjWyWOsLpaYJFKuj1dEgR1MPN8Q5YTGD13+Nbz6nHsv86DyrT7FCWYCiE3WNsI3lKvmFg3RBdhrQqxpBVYCuNP3e+tujLnjCyI8AgmtDcaLEZ0Ml41klk8qi78cY2ydCacdx9OrSGFRXi3jpNB9EHi9EWyDagHb349zakQmZ/LbcA8kwnQ6GQz5eqkpr2ZFquG9Ou27233Pl5ek180SHhCFUvI7E9u9ApEPx7Hu51e1ow3Pu0b+kUG33VaPpEtojvVBQ/ion4abCfktvxgFEbLd/or0PkCbFPrXpg8RWdgKA+c7sWdDa2CZI+TYs7sSHWN44zrrlNOI6L7/T3By6Jtis/dOQG+8sIl8+rN68/uuKuUapZ1jI4iUglctqr42JdbNSWpYG1Lupnp5R0jJQ8=');
INSERT INTO `weixin_log` VALUES ('16', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438758168', 'text', '饺子', null, null, '6179419278621378901', null, null, null, null, null, null, null, 'AxHK/519wLt2IrhGuqYUwEf/REXJtwfRUERR0rXC+1nAYRqugSHLAeLrb00CLwY6SseaqxxvSiHrgEg9F9qWG1rD5QkOXDODGn3N85bNXpx6sD2eZqj+M9ilQrZmoYhg1dD+mVkT3GAE7GhZXhsj/fZSOdmY0qyTqxpZoDONa7qqfL/KtNaEExgILkVFYUocHTxK1Rup3R8cAQyFXnjtyk22pekkIxrd1R+J370aazXw95wimdkkAn9/zY4ZmhN7EpARAYmxQj75nZnDj3Johw/ZicTrD9P+CxLRbJubpZLXS2+OAgKTrVdPgc3DxHe+14NYXbyq0l7BaSe+t61bNxKIHmgHavItqUGdA9X3JZvP3aSh8nzYAfhvRpCIoGfX5IAgcEcttv2h2kxe9OKFboTUAqy96B8nizWhqz2C/W4=');
INSERT INTO `weixin_log` VALUES ('17', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438758173', 'text', '@饺子', null, null, '6179419300096215392', null, null, null, null, null, null, null, 'eHAQ5JLiM7jBRC42L4w3jyJrDW37lT6ox6yWLAIlTsr5eLqT6P9jeoog4cmzoVk4KUibY81Sou/dAWheXNsWbhh3ddwHZEZwn6taskMTVw+tPZbOKWy2MJ8rR7qbos6yEDvggkJ56q76+FIIaFPgmaJ9AKbuAXlHZzruR2T09P/HtX34F/5ON3ayiBCXZMNLjb3sbE5lDfHRFXqfYhuuvioXnvXq9O+ERSBmLppvCFuUKfGneQ7soGMnZaybWpdhQm9Ik++b60nWm7x+6Uv2qRjwb1IJR/k/wCzo2suNw+ts4Ep1/lR/9u8LiilK2y8U5g2Sr7JQVem+axAWZ+MiJoRlJQREMjncLcQKvPwSizols05R+WzNX8AQnt0ncH0Q5R6nVMpV4v/b1eCpkOx7UxsuBt7j6+Cb2DzuWoINetw=');
INSERT INTO `weixin_log` VALUES ('18', 'gh_e6b57e7f6717', 'o5mg8t7yo_1hkuTTw2OkjxTDv9S4', '1438764150', 'text', '1', null, null, '6179444971115747782', null, null, null, null, null, null, null, 'i4UXZBXbpTxdDY9/03bIYmEcnvp0yPaBI5TBYRH8VbiDKl9/cQhtCRHal3QppyXIu/CeInZrWvFoRRQMCqZLGwAsxvLmIQH7VaT3wvylWojjXwestLV12Bya63yHV6MbTSoExSJm6G8pZcGge9NUWbAUimlJclof8R3Q3/eCquDKNBm5n9jPnLPQlJasA0fIAAcaUIlN7So5B8AIaTzdxF5aKeg4BUUioRLvhcfrE6xDz7bSf//W/yxcQZ1BP4o9zZ6QZEVdAcVz0yzG0/ldH3npsPmFtu57sqN3tt1v5Y4d92eDefiABeEOZyvFSojhFJLmaa/MoFfjyuFcIJ9llhHspE10UHmVvwAi0ds55/dM4cgfgH//aTv0o9h+sw1XyvrMPsS+8+9MLwgXG+ho7V7KxcLkaVyU4lvYEqjCEkE=');
