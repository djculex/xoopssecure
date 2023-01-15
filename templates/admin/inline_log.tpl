<div class="row justify-content-md-center">
    <{foreach item=arr from=$result}>
    <style type="text/css">
        <{$arr.css}>
    </style>
    <div class="col-md-8">
        <fieldset class="border p-2" style="margin: 3% 0px;">
            <div class="blog-entry ftco-animate d-md-flex">
                <div class="text text-2 pl-md-2">
                    <h3 class="mb-2">
                        <a href="single.html">
                            <p class="text-left"><{$arr.filename}></p>
                        </a>
                    </h3>
                    <div class="meta-wrap">
                        <p class="meta">
                            <span>
                        <h6><small><{$arr.humantime}></small></h6></span>
                        <span>
                            <h2><strong><{$arr.title}></strong></h2>
                        </span>
                        <{if $arr.linenumber != '0'}>
                        <span>
                            <h4>In line number.: <{$arr.linenumber}></h4>
                        </span>
                        <{/if}>
                        </p>
                    </div>
                    <p class="mb-2">
                    <pre style="overflow-x: auto;width: 75rem;height: 200px;overflow-y: hidden;"><{$arr.content}></pre>
                    </p>
                    <p><a href="#" class="btn-custom">Delete<span class="ion-ios-arrow-forward"></span></a></p>
                </div>
            </div>
        </fieldset>
    </div>
    <{/foreach}>
</div>
