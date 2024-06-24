@extends('layouts.agency') 
@section('content') 


<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Update Departments</h1>
            </div>
           
        </div>
        <div class="col-md-12 task-form">
        <form action="{{url('saveupdatedepartments')}}" method="post" id="form-task" data-action="" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <input type="hidden" name="id" id="contact" class="form-control" required value="{{ isset($data) ? $data->id : '' }}" />
            <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Department ID</label>
                        <input type="text" class="form-control task_date" name="dept_id" id="dept_id" placeholder="ID" required value="{{ isset($data) ? $data->department_id : '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">Department Name</label>
                        <input type="text" name="dept_name" id="dept_name" class="form-control contact_task_id" required value="{{ isset($data) ? $data->department_name : '' }}"/>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="dept_logo">Department Logo</label>
                        <input type="file" name="dept_logo" id="dept_logo" class="form-control"/>
                    </div>
                </div>


                
                <div class="col-md-6 pt-4">
                    <div class="form-group mt-4 text-right">
                        <input type="submit" class="btn btn-secondary" value="Submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
            @php 
                $dept=\DB::table('departments')->where('id',$data->id)->first();
                $theme_style = $dept->theme_style;
            @endphp
            <?php 
                $theme_style = json_decode($theme_style);
            ?>
            
            <div class="col-md-6 offset-md-5 pt-5">
                <div class="form-group col-md-12 py-2 alert alert-success" role="alert" style="display:none;" id="message">Settings Saved !</div>
                <div class="form-group col-md-12 py-2">
                    <strong class="mb-2">Theme Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <strong class="mb-2">Body Colors Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <label>Background</label>
                    <input type="color" id="bg-color" onchange="changeBg(this)" class="form-control" value="<?php if($theme_style->bg_color){ echo $theme_style->bg_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Foreground</label>
                    <input type="color" id="bg-color" onchange="changeForeground(this)" class="form-control" value="<?php if($theme_style->font_color){ echo $theme_style->font_color;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Border Color</label>
                    <input type="color" id="bg-color" onchange="changeBorder(this)" class="form-control" value="<?php if($theme_style->border){ echo $theme_style->border;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Headings</label>
                    <input type="color" id="bg-color" onchange="headingColor(this)" class="form-control" value="<?php if($theme_style->heading){ echo $theme_style->heading;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Paragraphs</label>
                    <input type="color" id="bg-color" onchange="paragraphColor(this)" class="form-control" value="<?php if($theme_style->paragraph){ echo $theme_style->paragraph;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Labels</label>
                    <input type="color" id="bg-color" onchange="lableColor(this)" class="form-control" value="<?php if($theme_style->lable){ echo $theme_style->lable;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <strong class="mb-2">Buttons Colors Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <label>Primary Background</label>
                    <input type="color" id="bg-color" onchange="btnPrimaryChangeBg(this)" class="form-control" value="<?php if($theme_style->btn_primary_bg_color){ echo $theme_style->btn_primary_bg_color;}else{ echo "#fd5c29";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Primary Foreground</label>
                    <input type="color" id="bg-color" onchange="btnPrimaryChangeForeground(this)" class="form-control" value="<?php if($theme_style->btn_primary_font_color){ echo $theme_style->btn_primary_font_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Secondary Background</label>
                    <input type="color" id="bg-color" onchange="btnSecondaryChangeBg(this)" class="form-control" value="<?php if($theme_style->btn_secondary_bg_color){ echo $theme_style->btn_secondary_bg_color;}else{ echo "#6c757d";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Secondary Foreground</label>
                    <input type="color" id="bg-color" onchange="btnSecondaryChangeForeground(this)" class="form-control" value="<?php if($theme_style->btn_secondary_font_color){ echo $theme_style->btn_secondary_font_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Danger Background</label>
                    <input type="color" id="bg-color" onchange="btnDangerChangeBg(this)" class="form-control" value="<?php if($theme_style->btn_danger_bg_color){ echo $theme_style->btn_danger_bg_color;}else{ echo "#dc3545";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Danger Foreground</label>
                    <input type="color" id="bg-color" onchange="btnDangerChangeForeground(this)" class="form-control" value="<?php if($theme_style->btn_danger_font_color){ echo $theme_style->btn_danger_font_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Info Background</label>
                    <input type="color" id="bg-color" onchange="btnInfoChangeBg(this)" class="form-control" value="<?php if($theme_style->btn_info_bg_color){ echo $theme_style->btn_info_bg_color;}else{ echo "#138496";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Info Foreground</label>
                    <input type="color" id="bg-color" onchange="btnInfoChangeForeground(this)" class="form-control" value="<?php if($theme_style->btn_info_font_color){ echo $theme_style->btn_info_font_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <strong class="mb-2">Input Colors Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <label>Background</label>
                    <input type="color" id="bg-color" onchange="txtChangeBg(this)" class="form-control" value="<?php if($theme_style->text_bg_color){ echo $theme_style->text_bg_color;}else{ echo "#ffffff";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Foreground</label>
                    <input type="color" id="bg-color" onchange="txtChangeForeground(this)" class="form-control" value="<?php if($theme_style->text_font_color){ echo $theme_style->text_font_color;}else{ echo "#000000";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <strong class="mb-2">Icon Color Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <label>Color</label>
                    <input type="color" id="bg-color" onchange="iconColor(this)" class="form-control" value="<?php if($theme_style->icon_color){ echo $theme_style->icon_color;}else{ echo "#444444";}?>"/>
                </div>
                <div class="form-group col-md-12">
                    <label>Active Color</label>
                    <input type="color" id="bg-color" onchange="iconActiveColor(this)" class="form-control" value="<?php if($theme_style->active_icon_color){ echo $theme_style->active_icon_color;}else{ echo "#fd5c29";}?>"/>
                </div>
                <div class="form-group col-md-12" style="padding-top:20px;padding-bottom:20px;">
                    <input type="button" name="submit" value="Save Settings" class="btn n-project" id="save_setting"/>
                    <input type="button" name="submit" value="Default UI Settings" class="btn btn-secondary" id="default_setting"/>                    
                </div>
            </div>

    </div>
</div>
<script>
   
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script>
    var themeSetting = {
        _token : $("input[name='_token']").val(),
        bg_color : '<?= $theme_style->bg_color ? $theme_style->bg_color : null ?>',
        font_color : '<?= $theme_style->font_color ? $theme_style->font_color : null ?>',
        heading : '<?= $theme_style->heading ? $theme_style->heading : null ?>',
        paragraph : '<?= $theme_style->paragraph ? $theme_style->paragraph : null ?>',
        lable : '<?= $theme_style->lable ? $theme_style->lable : null ?>',
        border : '<?= $theme_style->border ? $theme_style->border : null ?>',
        btn_primary_bg_color : '<?= $theme_style->btn_primary_bg_color ? $theme_style->btn_primary_bg_color : null ?>',
        btn_primary_font_color : '<?= $theme_style->btn_primary_font_color ? $theme_style->btn_primary_font_color : null ?>',
        btn_secondary_bg_color : '<?= $theme_style->btn_secondary_bg_color ? $theme_style->btn_secondary_bg_color : null ?>',
        btn_secondary_font_color : '<?= $theme_style->btn_secondary_font_color ? $theme_style->btn_secondary_font_color : null ?>',
        btn_danger_bg_color : '<?= $theme_style->btn_danger_bg_color ? $theme_style->btn_danger_bg_color : null ?>',
        btn_danger_font_color : '<?= $theme_style->btn_danger_font_color ? $theme_style->btn_danger_font_color : null ?>',
        btn_info_bg_color : '<?= $theme_style->btn_info_bg_color ? $theme_style->btn_info_bg_color : null ?>',
        btn_info_font_color : '<?= $theme_style->btn_info_font_color ? $theme_style->btn_info_font_color : null ?>',
        text_bg_color : '<?= $theme_style->text_bg_color ? $theme_style->text_bg_color : null ?>',
        text_font_color : '<?= $theme_style->text_font_color ? $theme_style->text_font_color : null ?>',
        text_border : '<?= $theme_style->text_border ? $theme_style->text_border : null ?>',
        icon_color : '<?= $theme_style->icon_color ? $theme_style->icon_color : null ?>',
        active_icon_color : '<?= $theme_style->active_icon_color ? $theme_style->active_icon_color : null ?>',
    }

    function changeBg(element){
        $('body').css('background',$(element).val())
        themeSetting.bg_color = $(element).val()
        console.log(themeSetting)
    }
    function headingColor(element){
        $('h1,h2,h3,h4,h5,h6').css('color',$(element).val())
        themeSetting.heading = $(element).val()
        console.log(themeSetting)
    }
    function paragraphColor(element){
        $('p').css('color',$(element).val())
        themeSetting.paragraph = $(element).val()
        console.log(themeSetting)
    }
    function lableColor(element){
        $('span,strong,label,small').css('color',$(element).val())
        themeSetting.lable = $(element).val()
        console.log(themeSetting)
    }
    function changeBorder(element){
        $('.border-right').css('border-right','1px solid '+$(element).val())
        $('.border-left').css('border-left','1px solid '+$(element).val())
        $('.border-top').css('border-top','1px solid '+$(element).val())
        $('.border-bottom').css('border-bottom','1px solid '+$(element).val())
        themeSetting.border = $(element).val()
        console.log(themeSetting)
    }
    function headingColor(element){
        $('h1,h2,h3,h4,h5,h6').css('color',$(element).val())
        themeSetting.heading = $(element).val()
        console.log(themeSetting)
    }
    function changeForeground(element){
        $('body').css('color',$(element).val())
        themeSetting.font_color = $(element).val()
        console.log(themeSetting)
    }
    function btnPrimaryChangeBg(element){
        $('.btn.n-project').css('background-color',$(element).val())
        themeSetting.btn_primary_bg_color = $(element).val()
        console.log(themeSetting)
    }
    function btnPrimaryChangeForeground(element){
        $('.btn.n-project').css('color',$(element).val())
        themeSetting.btn_primary_font_color = $(element).val()
        console.log(themeSetting)
    }
    function btnSecondaryChangeBg(element){
        $('.btn.btn-secondary').css('background-color',$(element).val())
        themeSetting.btn_secondary_bg_color = $(element).val()
        console.log(themeSetting)
    }
    function btnSecondaryChangeForeground(element){
        $('.btn.btn-secondary').css('color',$(element).val())
        themeSetting.btn_secondary_font_color = $(element).val()
        console.log(themeSetting)
    }
    function btnDangerChangeBg(element){
        $('.bg-danger').css('background-color',$(element).val())
        themeSetting.btn_danger_bg_color = $(element).val()
        console.log(themeSetting)
    }
    function btnDangerChangeForeground(element){
        $('.bg-danger').css('color',$(element).val())
        themeSetting.btn_danger_font_color = $(element).val()
        console.log(themeSetting)
    }
    function btnInfoChangeBg(element){
        $('.btn-info').css('background-color',$(element).val())
        themeSetting.btn_info_bg_color = $(element).val()
        console.log(themeSetting)
    }
    function btnInfoChangeForeground(element){
        $('.btn-info').css('color',$(element).val())
        themeSetting.btn_info_font_color = $(element).val()
        console.log(themeSetting)
    }
    function txtChangeBg(element){
        $('.form-control').css('background',$(element).val())
        $('.form-control').css('border',0)
        themeSetting.text_border = 0
        themeSetting.text_bg_color = $(element).val()
        console.log(themeSetting)
    }
    function txtChangeForeground(element){
        $('.form-control').css('color',$(element).val())
        themeSetting.text_font_color = $(element).val()
        console.log(themeSetting)
    }
    function iconColor(element){
        $('.fas').css('color',$(element).val())
        themeSetting.icon_color = $(element).val()
        console.log(themeSetting)
    }
    function iconActiveColor(element){
        $('.nav-active > i').css('color',$(element).val())
        themeSetting.active_icon_color = $(element).val()
        console.log(themeSetting)
    }

    $("#save_setting").click(function(){
        $.ajax({
            url: "{{url('departmentChangeTheme/'.$dept->id)}}",
            type: "POST",
            data: themeSetting,
            dataType: 'json',
            success : function(res){
                if(res){
                    $("#message").toggle()
                }
            }
        })
    })
    $("#default_setting").click(function(){
        themeSetting = {
            _token : $("input[name='_token']").val(),
            bg_color : null,
            font_color : null,
            heading : null,
            paragraph : null,
            lable : null,
            border : null,
            btn_primary_bg_color : null,
            btn_primary_font_color : null,
            btn_secondary_bg_color : null,
            btn_secondary_font_color : null,
            btn_danger_bg_color : null,
            btn_danger_font_color : null,
            btn_info_bg_color : null,
            btn_info_font_color : null,
            text_bg_color : null,
            text_font_color : null,
            text_border : null,
            icon_color : null,
            active_icon_color : null,
        }
        $.ajax({
            url: "{{url('departmentChangeTheme/'.$dept->id)}}",
            type: "POST",
            data: themeSetting,
            dataType: 'json',
            success : function(res){
                if(res){
                    $("#message").toggle()
                }
            }
        })
    })
</script>



@endsection