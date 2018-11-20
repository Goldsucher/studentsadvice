<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <section class="main-timeline-section">
                <div class="timeline-start"></div>
                <div class="conference-center-line"></div>
                <div class="conference-timeline-content">
                    {foreach from=$timeline key=semester item=datapacks}
                        {if $datapacks@first}
                            <div class="hedding-title">Imatrikulation</div>
                        {/if}
                        {if {counter} %2 neq 0 }
                            <div class="timeline-article content-right-container">
                        {else}
                            <div class="timeline-article content-left-container">
                        {/if}
                                <div class="content-date">
                                    <span>{$semester}</span>
                                </div>
                                <div class="meta-date"></div>
                                <div class="content-box">
                                    <div class="title-description">
                                        {foreach from=$datapacks item=datapack}
                                            <strong> {$datapack.Titel}</strong> - Note: {$datapack.Note} - {$datapack.Versuche}
                                            <br/>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                            {if $datapacks@last}
                                <div class="hedding-title">Abschluss</div>
                            {/if}
                    {/foreach}
                </div>
                <div class="timeline-end"></div>
            </section>
        </div>
    </div>
</div>
