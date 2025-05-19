<div class="footer">
    <?php
    $general = App\Models\GeneralSetting::first();
    ?>
    <div class="copyright">
        <!-- <p>Copyright © Designed &amp; Developed by <a href="javascript:void(0);">IrFan MirZa</a> {{date('Y')}}
        </p> -->
        <p>
            {{$general->copy_r}}
        </p>
    </div>
</div>
