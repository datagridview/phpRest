<!DOCTYPE html>
<html>
<head>
    <script src="js/face-api.js"></script>
    <script src="js/commons.js"></script>
    <script src="js/faceDetectionControls.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css">
    <link rel="stylesheet" href="live2d/css/live2d.css"/>
    <link rel="stylesheet" href="css/easyui.css"/>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body style="background-color: #EBEEEF">

<!--live2d Model-->

<div id="landlord" style="position: absolute;left: 50px;top: 100px">
    <div class="message card-panel teal lighten-2" style="opacity:0"></div>
    <canvas id="live2d" height="500px" class="live2d"></canvas>
    <div id="hide-button" class="btn-floating btn-large waves-effect waves-light">hide</div>
</div>

<!--main body-->
<div style="text-align: center; background-color: #EBEEEF">
    <div class="center-content page-container" >
        <div class="progress" id="loader">
            <div class="indeterminate"></div>
        </div>
        <div style="position: relative" class="margin">
            <video onloadedmetadata="onPlay(this)" id="inputVideo" autoplay muted playsinline></video>
            <canvas id="overlay" />
        </div>
        <div class="row side-by-side" style="display: none">
            <!-- face_detector_selection_control -->
            <div id="face_detector_selection_control" class="row input-field" style="margin-right: 20px; text-align: left">
                <select id="selectFaceDetector">
                    <option value="ssd_mobilenetv1">SSD Mobilenet V1</option>
                    <option value="tiny_face_detector">Tiny Face Detector</option>
                    <option value="mtcnn">MTCNN</option>
                </select>
                <label>Select Face Detector</label>
            </div>
            <!-- face_detector_selection_control -->

            <!-- check boxes -->
            <div class="row" style="width: 220px;text-align: left">
                <input type="checkbox" id="withFaceLandmarksCheckbox" onchange="onChangeWithFaceLandmarks(event)" />
                <label for="withFaceLandmarksCheckbox">Detect Face Landmarks</label>
                <input type="checkbox" id="hideBoundingBoxesCheckbox" onchange="onChangeHideBoundingBoxes(event)" />
                <label for="hideBoundingBoxesCheckbox">Hide Bounding Boxes</label>
            </div>
            <!-- check boxes -->

            <!-- fps_meter -->
            <div id="fps_meter" class="row side-by-side" style="text-align: left">
                <div>
                    <label for="time">Time:</label>
                    <input disabled value="-" id="time" type="text" class="bold">
                    <label for="fps">Estimated Fps:</label>
                    <input disabled value="-" id="fps" type="text" class="bold">
                </div>
            </div>
            <!-- fps_meter -->
        </div>

        <!-- ssd_mobilenetv1_controls -->
        <span id="ssd_mobilenetv1_controls" style="text-align: left;display: none">
          <div class="row side-by-side"  style="display: none">
            <div class="row">
              <label for="minConfidence">Min Confidence:</label>
              <input disabled value="0.5" id="minConfidence" type="text" class="bold">
            </div>
            <button
                class="waves-effect waves-light btn"
                onclick="onDecreaseMinConfidence()"
            >
              <i class="material-icons left">-</i>
            </button>
            <button
                class="waves-effect waves-light btn"
                onclick="onIncreaseMinConfidence()"
            >
              <i class="material-icons left">+</i>
            </button>
          </div>
        </span>
        <!-- ssd_mobilenetv1_controls -->

        <!-- tiny_face_detector_controls -->
        <span id="tiny_face_detector_controls" style="text-align: left;display: none">
          <div class="row side-by-side">
            <div class="row input-field" style="margin-right: 20px;">
              <select id="inputSize">
                <option value="" disabled selected>Input Size:</option>
                <option value="160">160 x 160</option>
                <option value="224">224 x 224</option>
                <option value="320">320 x 320</option>
                <option value="416">416 x 416</option>
                <option value="512">512 x 512</option>
                <option value="608">608 x 608</option>
              </select>
              <label>Input Size</label>
            </div>
            <div class="row">
              <label for="scoreThreshold">Score Threshold:</label>
              <input disabled value="0.5" id="scoreThreshold" type="text" class="bold">
            </div>
            <button
                class="waves-effect waves-light btn"
                onclick="onDecreaseScoreThreshold()"
            >
              <i class="material-icons left">-</i>
            </button>
            <button
                class="waves-effect waves-light btn"
                onclick="onIncreaseScoreThreshold()"
            >
              <i class="material-icons left">+</i>
            </button>
          </div>
        </span>
        <!-- tiny_face_detector_controls -->

        <!-- mtcnn_controls -->
        <span id="mtcnn_controls" style="text-align: left;display: none">
          <div class="row side-by-side">
            <div class="row">
              <label for="minFaceSize">Minimum Face Size:</label>
              <input disabled value="20" id="minFaceSize" type="text" class="bold">
            </div>
            <button
                class="waves-effect waves-light btn"
                onclick="onDecreaseMinFaceSize()"
            >
              <i class="material-icons left">-</i>
            </button>
            <button
                class="waves-effect waves-light btn"
                onclick="onIncreaseMinFaceSize()"
            >
              <i class="material-icons left">+</i>
            </button>
          </div>
        </span>
        <!-- mtcnn_controls -->

        <div style="text-align: left;display: none">
            <a class="waves-effect waves-light btn" href="video.html">
                <i class="material-icons">video test</i>
            </a>
        </div>

        <div style="margin-top: 2em">
            <label for="csiec">csiec:</label>
            <input id="csiec" disabled type="text"/>
            <label for="userwords" id="user">user</label>
            <input id="userwords" type="text"/>
        </div>

        <a class="btn-floating btn-large pulse" id="btnSend"><i class="material-icons">cloud</i></a>
        <div>关于以下数学多边形的关系：等腰直角三角形、直角三角形、等腰三角形、等边三角形、三角形、正方形、矩形、菱形、平行四边形、梯形、四边形。
        <br>请回答对、错.
        <form name=f1 action="polygon.php" method="post"><p class="p-font">
                <?php
                require_once '../config.php';
                global $CFG,$DB,$USER;
                require_login();
                echo $USER->username;  // ." ". $USER->password;
                error_reporting(0);
                // Turn off all error reporting
                error_reporting(0);

                // Report simple running errors
                //error_reporting(E_ERROR | E_WARNING | E_PARSE);

                // Reporting E_NOTICE can be good too (to report uninitialized
                // variables or catch variable name misspellings ...)
                //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

                // Report all errors except E_NOTICE
                //error_reporting(E_ALL & ~E_NOTICE);

                // Report all PHP errors (see changelog)
                //error_reporting(E_ALL);

                // Report all PHP errors
                //error_reporting(-1);
                //$user_info_field = $DB->get_record('user_info_field', array('shortname'=>'MemleticsLS') );

                if($_POST["answer"] && $_POST["qid"])
                {
                    //lhx 20190507 s
                    $pregs = 'select|insert|update|CR|document|LF|eval|delete|script|alert|\'|\/\*|\#|\--|\ --|\/|\*|\-|\+|\=|\~|\*@|\*!|\$|\%|\^|\&|\(|\)|\/|\/\/|\.\.\/|\.\/|union|into|load_file|outfile|sleep';

                    if(preg_match($pregs, $_POST["qid"]) == 1)
                    {
                        return;
                    }
                    //lhx 20190507 e
                    $answer=$_POST["answer"];
                    $record = new stdClass();
                    $record->username  = $USER->username;
                    $record->question_id = $_POST["qid"];
                    $record->begin_time = $_POST["begin_time"];
                    $record->end_time = time();
                    //$record->used_time = $record->end_time-$record->begin_time;
                    $record->score=100;
                    $record->answer=$answer;

                    $sql = "select * from questions where category='判断' and content='".$_POST["content"]."' and answer='".$_POST["answer"]."';"; //id=".$_POST["qid"]." and answer='".$_POST["answer"]."';";
                    //echo $sql;
                    $score = $DB->get_records_sql($sql);
                    if (empty($score))	//no right answer
                    {$record->score=0;
                        echo "你的回答<strong>错</strong>了！再出一题：";
                    }
                    else
                        echo "你的回答<strong>对</strong>了！再出一题：";
                    //var_dump($record);
                    //$DB->insert_record('question_attempts', $record); //mdl_

                    //JJY Attention! can't use ; at the sql end! Otherwise Moodle Error!
                    $sql="insert into question_attempts (username, question_id,begin_time,end_time,score, answer) values('".$record->username."',". $record->question_id.",".$record->begin_time.",".$record->end_time.",".$record->score.",'".$record->answer."')";
                    //echo $sql;
                    //$DB->insert_record_sql($sql);
                    $re=$DB->execute($sql);
                }



                $sql = "select * from questions where category='判断';";
                $questions = $DB->get_records_sql($sql);
                $counts=count($questions);
                $notfound=true;
                while($notfound)
                    //foreach($questions as $question) //get the  learn style survey id
                {//$random_no=random_int(0,$counts-1);//php 7
                    $random_no=rand(0,$counts-1);
                    $qid = $questions[$random_no]->id;
                    $attemps = $DB->get_records_sql("select * from question_attempts where username='".$USER->username."' and question_id=". $qid.";");
                    if (empty($attempts))	//no record yet
                    {
                        echo $questions[$random_no]->content.'<br>';
                        echo "<input type=hidden name='qid' value=".$qid." />";
                        echo "<input type=hidden name='begin_time' value=".time()." />";
                        echo "<input type=hidden name='content' value='". $questions[$random_no]->content."' />";
                        $notfound=false;
                    }
                }
                if($notfound) //all questions have been attempted
                {
                    echo "所有题目都做过了.<br>";
                    $attempts = $DB->get_records_sql("select question_id, max(score) as max_score from question_attempts where username='".$USER->username."' and score<100 group by question_id order by max_score;");
                    if (empty($attempts))	//no record score=100
                        echo "并且所有题目都做对了!<br>";
                    else //only select the questions with false answer!
                    {echo "但是还有错题。再出一个得分最低的题目：";
                        $qid = $attempts[0]->question_id; //the min score
                        $questions = $DB->get_records_sql("select * from questions where id=". $qid.";");
                        echo $questions[$random_no]->content.'<br>';
                        echo "<input type=hidden name='qid' value=".$qid." />";
                        echo "<input type=hidden name='begin_time' value=".time()." />";
                        echo "<input type=hidden name='content' value='". $questions[$random_no]->content."' />";
                    }

                }
                /*	if(strlen($_POST["text"]) > 10 )
                    include "Snoopy.class.php";
                    $snoopy = new Snoopy;
                    $url="http://localost:4701";
                    //echo "here2";
                    //$submit_vars["type"] = optional_param('type', 'moodle_login', PARAM_RAW);
                    $submit_vars["type"] = "polygon";
                    //$submit_vars["type"] = "login";
                    //$submit_vars["text"] = optional_param('text', 'Hello', PARAM_RAW);
                    $submit_vars["text"] =$_POST["text"];
                    $submit_vars['text']=str_replace('\\','', $submit_vars["text"]);
                    //echo "here1";

                    $submit_vars["username"] =$USER->username;
                    $submit_vars["password"] =$USER->password;

                    //echo $ip."-".$submit_vars["username"]."<br>";

                //print_r($submit_vars) ;
                    if($snoopy->submit($url,$submit_vars))			echo $snoopy->results;
                    else		echo "Sorry I have now rest and will chat with you tomorrow.\n";
                */


                ?>

            <p class="p-font"> 请输入答案:<input type="text" name="answer" size=10 />
                <input type="submit" value="Send"/>
        </form><p class="p-font"> <a href="AI_examples.html">Back home</a>
        </>

        <audio id="audio" autoplay="autoplay"></audio>
    </div>
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="live2d/js/live2d.js"></script>
<script type="text/javascript">
    let message_Path = './live2d/';
</script>
<script>
    var LAppDefine = {
        // 调试，true时会在console里显示日志
        DEBUG_LOG: true,
        DEBUG_MOUSE_LOG: true, // 鼠标相关日志
        //  全部设定
        //这里配置canvsa元素的id
        CANVAS_ID: "live2d",
        //是否开启滚轮缩放，默认true
        IS_SCROLL_SCALE: false,
        // 画面最大缩放级别
        VIEW_MAX_SCALE: 2,
        // 画面最小缩放级别
        VIEW_MIN_SCALE: 0.8,

        VIEW_LOGICAL_LEFT: -1,
        VIEW_LOGICAL_RIGHT: 1,

        VIEW_LOGICAL_MAX_LEFT: -2,
        VIEW_LOGICAL_MAX_RIGHT: 2,
        VIEW_LOGICAL_MAX_BOTTOM: -2,
        VIEW_LOGICAL_MAX_TOP: 2,

        // 动作优先级常量
        PRIORITY_NONE: 0,
        PRIORITY_IDLE: 1,
        PRIORITY_NORMAL: 2,
        PRIORITY_FORCE: 3,

        //是否绑定切换模型按钮
        IS_BIND_BUTTON: false,
        //绑定按钮元素id
        // BUTTON_ID : "Change",
        //是否开启模型切换完成之前禁止按钮点击的选项，默认为true
        // IS_BAN_BUTTON : true,
        //设置按钮禁止状态时的class，可自定义样式，前提是IS_BAN_BUTTON为true
        // BAN_BUTTON_CLASS : "inactive",
        //设置按钮正常状态时的class
        // NORMAL_BUTTON_CLASS : "active",
        //衣服切换模式 目前只支持两种 sequence-顺序 random-随机
        //需事先配置好json文件里的textures属性
        //暂不支持保存功能
        TEXURE_CHANGE_MODE: "sequence",
        IS_START_TEXURE_CHANGE: false,
        TEXURE_BUTTON_ID: "",
        /**
         *  模型定义
         自定义配置模型，同一数组内放置两个模型则为开启双模型
         三模型也只取数组里的前两个
         模型出现的顺序与数组一致
         这里请用相对路径配置
         */
        MODELS:
            [
                ["live2d/model/live2d-widget-model-haruto/assets/haruto.model.json"]
            ],

        // 与外部定义的json文件匹配
        MOTION_GROUP_IDLE: "idle", // 空闲时
        // MOTION_GROUP_TAP_BODY : "tap_body", // 点击身体时
        // MOTION_GROUP_FLICK_HEAD : "flick_head", // 抚摸头部
        // MOTION_GROUP_PINCH_IN : "pinch_in", // 放大时
        // MOTION_GROUP_PINCH_OUT : "pinch_out", // 缩小时
        // MOTION_GROUP_SHAKE : "shake", // 摇晃
        //如果有自定义的动作分组可以放在这里

        // 与外部定义json文件相匹配
        // HIT_AREA_HEAD : "head",
        // HIT_AREA_BODY : "body",

        //初始化的模型大小
        SCALE: 1.0,
        //新增属性，是否播放音频 默认为true
        IS_PLAY_AUDIO: true,
        //新增属性，audio标签id值
        AUDIO_ID: "audio"
    };
    mgr = InitLive2D();
</script>
<script type="text/javascript" src="live2d/js/message.js"></script>
<script type="text/javascript">
    $("#landlord").draggable();
</script>
<script type="text/javascript" src="js/faceRecognition.js"></script>
</body>
</html>