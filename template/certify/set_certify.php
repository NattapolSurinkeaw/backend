<?php

use function Matrix\divideby;


if (!getData::savePropertyCertify($_GET['id'])) {
    exit();
}

$propertyName = getData::getCertifyPropertyById($_GET['id'], 'name');
$propertyTitle = getData::getCertifyPropertyById($_GET['id'], 'title');
$propertyScore = getData::getCertifyPropertyById($_GET['id'], 'score');
$propertyDay = getData::getCertifyPropertyById($_GET['id'], 'day');
$propertyMonth = getData::getCertifyPropertyById($_GET['id'], 'month');
$propertyYear = getData::getCertifyPropertyById($_GET['id'], 'year');
$propertyImage1 = getData::getCertifyPropertyById($_GET['id'], 'image1');
$propertyImage2 = getData::getCertifyPropertyById($_GET['id'], 'image2');



$cTitle = getData::getCertifyTitle($_GET['id']);
$dateTime = explode(" ", getData::DateThai(date('Y-m-d H:i:s'), true));

?>

<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
<!-- <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap" rel="stylesheet"> -->
<link href="https://fonts.googleapis.com/css?family=Sarabun:300,400,500,600,700,800&display=swap" rel="stylesheet">
<style>
    * {
        /* font-family: 'Quicksand', sans-serif; */
    }

    body {
        width: 100%;
    }


    #certification>h1 {
        font-family: 'Sarabun', sans-serif !important;
    }

    @media print {

        h1 {
            color: black;
        }

        #control {
            display: none;
        }

        #certification {
            border: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow-y: unset !important;
        }

        #img {
            width: 100% !important;
            height: auto !important;
            /* border: 1px solid black; */
        }

        #image1,
        #image2 {
            border: 0px;
        }
    }

    @page {
        size: auto;
        margin: 0mm;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap" aria-hidden="true"></i> ตั้งค่าการพิมพ์ใบประกาศ
            <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                                    ?></a></li>
            <li class="active">ตั้งค่าการพิมพ์ใบประกาศ</li>
        </ol>
    </section>

    <div id="statusSaveWrapper" class="w-1/2 h-32 bg-teal-500 fixed flex justify-center items-center shadow-lg" style="z-index:100;left:50%;transform:translateX(-50%);top:-100px;transition: all 1s ease-in-out">
        <p id="statusSave" class="text-4xl text-center text-white">หน้านี้ ระบบจะทำการบันทึกให้อัตโนมัตินะครับ </p>
    </div>

    <section class="content pt-0">
        <div class="flex">
            <div class="w-full">

                <main class="flex">

                    <div class="bg-gray-100 border border-gray-500 m-4 relative" style="width:760px;height:85vh;overflow-y:scroll;" id="certification">
                        <img class="w-32 h-32 absolute cursor-move" data-id="<?= (empty($propertyImage1->cp_id) ? '0' : $propertyImage1->cp_id) ?>" src="<?= (!empty($propertyImage1->cp_img) ? $propertyImage1->cp_img : 'https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg') ?>" alt="<?= (empty($propertyImage1->cp_img) ? 'ยังไม่ได้อัพโหลดรูปภาพ' : $propertyImage1->cp_name) ?>" id="image1" style="transform: translateX(-50%);
                            width:<?= empty($propertyImage1->cp_size) ? '100' : $propertyImage1->cp_size ?>px;
                            height:<?= empty($propertyImage1->cp_weight) ? '100' : $propertyImage1->cp_weight ?>;
                            top:<?= empty($propertyImage1->cp_y) ? '820' : $propertyImage1->cp_y ?>px; 
                            left:<?= empty($propertyImage1->cp_y) ? '169px' : $propertyImage1->cp_x . 'px' ?>;">

                        <img class="w-32 h-32 absolute cursor-move" data-id="<?= (empty($propertyImage2->cp_id) ? '0' : $propertyImage2->cp_id) ?>" src="<?= (!empty($propertyImage2->cp_img) ? $propertyImage2->cp_img : 'https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg') ?>" alt="<?= (empty($propertyImage2->cp_img) ? 'ยังไม่ได้อัพโหลดรูปภาพ' : $propertyImage2->cp_name) ?>" id="image2" style="transform: translateX(-50%);
                            width:<?= empty($propertyImage2->cp_size) ? '100' : $propertyImage2->cp_size ?>px;
                            height:<?= empty($propertyImage2->cp_weight) ? '100' : $propertyImage2->cp_weight ?>;
                            top:<?= empty($propertyImage2->cp_y) ? '820' : $propertyImage2->cp_y ?>px; 
                            left:<?= empty($propertyImage2->cp_y) ? '533px' : $propertyImage2->cp_x . 'px' ?>;">

                        <h1 class="cursor-move absolute text-center text-3xl" id="nameShow" data-id="<?= (empty($propertyName->cp_id) ? 'undefined' : $propertyName->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyName->cp_y) ? '427' : $propertyName->cp_y ?>px; 
                            left:<?= empty($propertyName->cp_y) ? '50%' : $propertyName->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyName->cp_size) ? '18' : $propertyName->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyName->cp_weight) ? '400' : $propertyName->cp_weight ?>;
                ">
                            <?= empty($propertyName->cp_name) ? 'ชื่อตัวอย่าง นามสกุลสมมุติ' : $propertyName->cp_name ?>
                        </h1>
                        <h1 class="cursor-move absolute text-center text-3xl" id="titleShow" data-id="<?= (empty($propertyTitle->cp_id) ? 'undefined' : $propertyTitle->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyTitle->cp_y) ? '560' : $propertyTitle->cp_y ?>px; 
                            left:<?= empty($propertyTitle->cp_y) ? '50%' : $propertyTitle->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyTitle->cp_size) ? '18' : $propertyTitle->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyTitle->cp_weight) ? '400' : $propertyTitle->cp_weight ?>;"><?= $cTitle->title ?>
                        </h1>
                        <h1 class="cursor-move absolute text-center text-3xl" id="scoreShow" data-id="<?= (empty($propertyScore->cp_id) ? 'undefined' : $propertyScore->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyScore->cp_y) ? '640' : $propertyScore->cp_y ?>px; 
                            left:<?= empty($propertyScore->cp_y) ? '65%' : $propertyScore->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyScore->cp_size) ? '18' : $propertyScore->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyScore->cp_weight) ? '400' : $propertyScore->cp_weight ?>;">100
                        </h1>
                        <h1 class="cursor-move absolute text-center text-3xl" id="dayShow" data-id="<?= (empty($propertyDay->cp_id) ? 'undefined' : $propertyDay->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyDay->cp_y) ? '720' : $propertyDay->cp_y ?>px; 
                            left:<?= empty($propertyDay->cp_y) ? '36%' : $propertyDay->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyDay->cp_size) ? '18' : $propertyDay->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyDay->cp_weight) ? '400' : $propertyDay->cp_weight ?>;"><?= $dateTime[0] ?>
                        </h1>
                        <h1 class="cursor-move absolute text-center text-3xl" id="monthShow" data-id="<?= (empty($propertyMonth->cp_id) ? 'undefined' : $propertyMonth->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyMonth->cp_y) ? '720' : $propertyMonth->cp_y ?>px; 
                            left:<?= empty($propertyMonth->cp_y) ? '57%' : $propertyMonth->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyMonth->cp_size) ? '18' : $propertyMonth->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyMonth->cp_weight) ? '400' : $propertyMonth->cp_weight ?>;"><?= $dateTime[1] ?>
                        </h1>
                        <h1 class="cursor-move absolute text-center text-3xl" id="yearShow" data-id="<?= (empty($propertyYear->cp_id) ? 'undefined' : $propertyYear->cp_id) ?>" style="width:auto;transform: translateX(-50%);
                            top:<?= empty($propertyYear->cp_y) ? '720' : $propertyYear->cp_y ?>px; 
                            left:<?= empty($propertyYear->cp_y) ? '79%' : $propertyYear->cp_x . 'px' ?>;
                            font-size:<?= empty($propertyYear->cp_size) ? '18' : $propertyYear->cp_size . 'px' ?>;
                            font-weight:<?= empty($propertyYear->cp_weight) ? '400' : $propertyYear->cp_weight ?>;"><?= $dateTime[2] ?>
                        </h1>



                        <img class="mx-auto mt-20" style="width:30%;" src="https://www.srinagarindexcellencelab.kku.ac.th/upload/2019/08/1566982056_20190828034736.png" alt="">
                        <h1 class="text-center font-medium text-3xl my-4  mt-24">หน่วยภูมิคุ้มกันวิทยาคลินิก และเคมีคลินิก</h1>
                        <h1 class="text-center font-medium text-3xl my-4 ">งานห้องปฏิบัติการเวชศาสตร์ชันสูตร</h1>
                        <h1 class="text-center font-medium text-3xl my-4 ">โรงพยาบาลศรีนครินทร์ คณะแพทยศาสตร์ มหาวิทยาลัยขอนแก่น</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-24">รับรองว่า</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-12">..............................................................</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-24">ได้ผ่านการฝึกอบรมในหัวข้อเรื่อง</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-12">..............................................................</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-24">โดยได้คะแนนการทดสอบเท่ากับ ......................... %</h1>
                        <h1 class="text-center font-medium text-3xl my-4 mt-24">ออกให้ ณ วันที่ ............... เดือน ............................... พ.ศ. ................</h1>
                        <div class=" flex w-full mt-56">
                            <div class="w-1/2">
                                <h1 class="text-center font-medium text-3xl my-4 mt-8">..............................................................</h1>
                                <h1 class="text-center font-medium text-3xl my-4 ">(นางจันทร์เพ็ญ ศรีพรรณ์)</h1>
                                <h1 class="text-center font-medium text-3xl my-4 ">หัวหน้างานห้องปฏิบัติการเวชศาสตร์ชันสุตรวิทยาคลินิกและเคมีคลินิก</h1>
                            </div>
                            <div class="w-1/2">
                                <h1 class="text-center font-medium text-3xl my-4 mt-8">..............................................................</h1>
                                <h1 class="text-center font-medium text-3xl my-4 ">(นายปริญญา ประสงค์ดี)</h1>
                                <h1 class="text-center font-medium text-3xl my-4 ">หัวหน้าหน่วยภูมิคุ้มกัน</h1>
                            </div>
                        </div>

                    </div>


                    <section class="m-4 bg-gray-100 p-2 border border-gray-500 rounded" id="control" style="width:470px;">


                        <div class="flex">
                            <form id="formUploadImg" class="w-full">
                                <h2 class="text-2xl text-center">อัพโหลดรูปภาพ (ลายเซ็นที่1)</h2>
                                <input type="file" name="inputFile" id="inputFileImg" style="display:none;">

                                <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label>
                                <img id="img-handle-upload-image" src="<?= (!empty($propertyImage1->cp_img) ? $propertyImage1->cp_img : 'https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg') ?>" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">

                                <!-- <br> -->
                                <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                                <br>
                                <!-- <input type="hidden" value="<?= $pData->cp_id ?>" name="id"> -->
                                <input type="hidden" value="<?= $_GET['id'] ?>" name="ct_id">
                                <input type="hidden" value="image1" name="cp_type">
                                <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
                            </form>
                            <form id="formUploadImg2" class="w-full">
                                <h2 class="text-2xl text-center">อัพโหลดรูปภาพ (ลายเซ็นที่2)</h2>
                                <input type="file" name="inputFile" id="inputFileImg2" style="display:none;">

                                <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label>
                                <img id="img-handle-upload-image2" src="<?= (!empty($propertyImage2->cp_img) ? $propertyImage2->cp_img : 'https://www.srinagarindexcellencelab.kku.ac.th/upload/certify/168b89dadf5e0c1fee1ea8fcee0c23ca.jpg') ?>" style="margin:auto;display:block;width:200px;height:200px;cursor: pointer;padding: 20px;border: 1px solid #e3e3e3;" alt="">

                                <!-- <br> -->
                                <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                                <br>
                                <!-- <input type="hidden" value="<?= $pData->cp_id ?>" name="id"> -->
                                <input type="hidden" value="<?= $_GET['id'] ?>" name="ct_id">
                                <input type="hidden" value="image2" name="cp_type">
                                <button type="submit" class="btn kt:btn-success" style="display:block;margin-top:10px;margin-right:auto;margin-left:auto;padding:10px 40px;"><i class="fa fa-upload" aria-hidden="true"></i> ยืนยันอัพโหลดไฟล์</button>
                            </form>
                        </div>

                        <hr class="my-4 border border-gray-500">


                        <div class="flex">
                            <label for="nameInput" class="w-full py-1 text-2xl font-bold text-center">ตั้งค่า คุณสมบัติ ( <span id="settingPropertyName" style="color:red;">...</span> )</label>
                        </div>
                        <div class="">
                            <div class="flex my-2">
                                <label for="nameInput" class="w-4/12 py-1">Name: </label>
                                <input type="text" class=" border border-gray-400 py-1 pl-2 w-8/12 rounded" disabled id="nameInput">
                            </div>
                            <div class="flex my-2">
                                <label for="fontsizeInput" class="w-4/12 py-1" id="sizeText">Size: </label>
                                <input type="number" class=" border border-gray-400 py-1 pl-2 w-4/12 rounded" disabled id="fontsizeInput">
                                <label for="fontsizeInput" class="ml-2 w-4/12 py-1">px</label>
                            </div>
                            <div class="flex my-2 ">
                                <label for="fontweightInput" class="w-4/12 py-1" id="weightText">Weight: </label>
                                <input type="number" class=" border border-gray-400 py-1 pl-2 w-4/12 rounded" disabled min="100" max="900" step="100" id="fontweightInput">
                                <label for="fontweightInput" class="ml-2 w-4/12 py-1" id="weightExten">(100-900)</label>
                            </div>
                            <div class="flex my-2">
                                <label for="YInput" class="w-4/12 py-1">Y: </label>
                                <input type="number" class=" border border-gray-400 py-1 pl-2 w-4/12 rounded" disabled min="0" step="1" id="YInput">
                                <label for="YInput" class="ml-2 w-4/12 py-1">px</label>
                            </div>
                            <div class="flex my-2">
                                <label for="XInput" class="w-4/12 py-1">X: </label>
                                <input type="number" class=" border border-gray-400 py-1 pl-2 w-4/12 rounded" disabled min="0" step="1" id="XInput">
                                <label for="XInput" class="ml-2 w-4/12 py-1">px</label>
                            </div>
                        </div>
                        <div class="flex my-2 mt-10">
                            <input type="hidden" value="<?= $pData[$i]['cp_id'] ?>" id="cp_id">
                            <input type="hidden" value="<?= $_GET['id'] ?>" id="ct_id">
                            <input type="hidden" value="" id="cp_type">
                            <!-- <button id="btnSave" class="bg-green-500 cursor-not-allowed opacity-50 hover:bg-green-600 shadow-md py-4 w-1/2 mx-auto text-white rounded " onclick="saveCertifyProperty(event)">
                        <i class="fa fa-save"></i> Save
                    </button> -->

                        </div>
                        <div class="flex flex-col my-2 mt-16">
                            <hr class="border border-gray-500 w-full">
                            <button class="mt-20 bg-blue-500 hover:bg-blue-600 shadow-md py-4 w-1/2 mx-auto text-white rounded " onclick="window.print()">
                                <i class="fa fa-print"></i> print
                            </button>
                        </div>
                    </section>

                </main>



            </div>
        </div>
    </section>



</div>

<!-- popup status download  -->
<div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">


<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/certify/certify_property.js"></script>