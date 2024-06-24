@extends('layouts.client') @section('content') <div class="p-sm-4 p-3 project">
    <h1>My Profile</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post"
                    action="{{url('client/profile/update')}}" enctype="multipart/form-data"> @csrf<div class="row">
                        <div class="form-group col-md-12 py-2">
                            <strong>{{$user->email}}</strong>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Name</label>
                            <input type="text"
                                name="name"
                                class="form-control"
                                value="{{$user->name}}"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Username</label>
                            <input type="text"
                                name="username"
                                class="form-control"
                                value="{{$user->username}}"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Profile Picture</label>
                            <input type="file"
                                accept="image/*"
                                name="profile_picture"
                                class="form-control"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Password</label>
                            <input type="password"
                                name="password"
                                class="form-control"
                                value="" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Notifications</label>
                            <select name="notification_status"
                                class="form-control">
                                <option value="1"
                                    @if($user->notification_status==1) selected @endif>ON</option>
                                <option value="0"
                                    @if($user->notification_status==0) selected @endif>OFF</option>
                            </select>
                        </div>
                        <div class="col-md-12 my-2">
                            <input type="submit"
                                name="submit"
                                value="Update Profile"
                                class="btn n-project" />
                            <!-- <a href="{{route('client-deleteAccount')}}"
                                class="btn bg-danger deleteAccount btn-sm"><i class="far fa-trash-alt"></i> Delete Account</a> -->
                        </div>
                    </div>
                </form>
            </div>
            @php 
                $user=\DB::table('users')->where('id',\Auth::user()->id)->orderBy('id','desc')->first();
                $theme_style = $user->theme_style;
            @endphp
            <?php 
                $theme_style = json_decode($theme_style);
            ?>
            @if($user->theme_setting==1)
            <div class="col-md-6 col-sm-12">
                <div class="form-group col-md-12 py-2 alert alert-success" role="alert" style="display:none;" id="message">Settings Saved !</div>
                <div class="form-group col-md-12 py-2">
                    <strong class="mb-2">Theme Settings</strong>
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <label>Theme Logo</label>
                    <form method="post" id="logo-form" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="logo" id="logo" class="form-control"/>
                    </form>
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
            @endif
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$(".deleteAccount").on("click", function(e) {
    var $this = $(this);
    if (deleteStatus == false) {
        e.preventDefault();
        Swal.fire({
            title: "Delete Account",
            text: "Are you sure?",
            icon: "warning",
            confirmButtonText: "Yes",
        }).then((value) => {
            if (value.isConfirmed) {
                deleteStatus = true;
                window.location.href = $this.attr("href");
            }
        });
    }
})
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
    var _token = $("input[name='_token']").val()
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

    $("#logo").change(function(){
        $("#logo-form").submit()
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".header").find('img').attr("src",e.target.result)
        }
        reader.readAsDataURL($('#logo')[0].files[0])
    })

    $("#logo-form").submit(function(e){
        e.preventDefault()
        var formData = new FormData(this)
        $.ajax({
            type:'POST',
            url: "{{url('changeLogo/'.$user->id)}}",
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                console.log("success")
                console.log(data)
            },
            error: function(data){
                console.log("error")
                console.log(data)
            }
        })
    })

    $("#save_setting").click(function(){
        $.ajax({
            url: "{{url('admin/changeTheme/'.$user->id)}}",
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
            url: "{{url('admin/changeTheme/'.$user->id)}}",
            type: "POST",
            data: themeSetting,
            dataType: 'json',
            success : function(res){
                if(res){
                    $.ajax({
                        url: "{{url('removeLogo/'.$user->id)}}",
                        type: "GET",
                        success : function(res){
                            if(res){
                                window.location.reload()
                            }
                        }
                    })
                }
            }
        })
    })
</script> @endsection
