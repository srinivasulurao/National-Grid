<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="standard.php" clickstream="home"/>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
            <h1>#rn:msg:WERE_HERE_TO_HELP_LBL#</h1>
        </div>
        <div class="rn_SearchControls">
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
            <form method="get" action="/app/results">
                <rn:container source_id="KFSearch">
                    <div class="rn_SearchInput">
                        <rn:widget path="searchsource/SourceSearchField" initial_focus="true"/>
                    </div>
                    <rn:widget path="searchsource/SourceSearchButton" search_results_url="/app/results"/>
                </rn:container>
            </form>
        </div>
    </div>
</div>

<div class="rn_PageContent rn_Home">
<? /*
    <div class="rn_Container">
        <rn:widget path="navigation/VisualProductCategorySelector" numbered_pagination="true"/>
    </div>
*/ ?>

    <div class="rn_Container">
        <div class="rn_VisualProductCategorySelector">
            <div class="rn_Items">
                <div class="rn_Loading rn_Hidden"></div>

                <div class="rn_ScreenReaderOnly" role="alert">
                    New products loaded
                </div>

                <div class="rn_NavigationArea">
                    <span role="navigation" class="rn_BreadCrumb"><span class="rn_CurrentLevelBreadCrumb">&nbsp;</span></span>
                </div>

                <div class="rn_ItemGroup rn_ItemLevel1 rn_BaseGroup rn_Item_Base_SubItems">
                    <ul>
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/home">
                                    <img alt="" src="/euf/assets/images/nav_icons/home.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/home">
                                    Home</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/customer_feedback/list">
                                    <img alt="" src="/euf/assets/images/nav_icons/customer_feedback.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/customer_feedback/list">
                                    Customer Feedback</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/supplier_feedback/list">
                                    <img alt="" src="/euf/assets/images/nav_icons/supplier_feedback.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/supplier_feedback/list">
                                    Supplier Feedback</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/investigations/list">
                                    <img alt="" src="/euf/assets/images/nav_icons/investigations.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/customer_feedback/investigations/list">
                                    Investigations</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/action_items/list/">
                                    <img alt="" src="/euf/assets/images/nav_icons/action_items.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/action_items/list/">
                                    Action Items</a>
                                </div>
                            </div>
                        </li>
                        
                        <li class="rn_Item">
                            <div class="rn_VisualItemContainer">
                                <div class="rn_ImageContainer">
                                    <a class="rn_ItemLink" href="/app/answers/list">
                                    <img alt="" src="/euf/assets/images/nav_icons/knowledge_base.png" /></a>
                                </div>
        
                                <div class="rn_ActionContainer" style="height: 48px;">
                                    <a class="rn_ItemLink" href="/app/answers/list">
                                    Knowledge Base</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="rn_PopularKB">
        <div class="rn_Container">
            <h2>#rn:msg:POPULAR_PUBLISHED_ANSWERS_LBL#</h2>
            <rn:widget path="reports/TopAnswers" show_excerpt="true" limit="5"/>
            <a class="rn_AnswersLink" href="/app/answers/list#rn:session#">#rn:msg:SHOW_MORE_PUBLISHED_ANSWERS_LBL#</a>
        </div>
    </div>

<? /*
    <div class="rn_PopularSocial">
        <div class="rn_Container">
            <h2>#rn:msg:RECENT_COMMUNITY_DISCUSSIONS_LBL#</h2>
            <rn:widget path="discussion/RecentlyAnsweredQuestions" show_excerpt="true" maximum_questions="5"/>
            <a class="rn_DiscussionsLink" href="/app/social/questions/list/kw/*#rn:session#">#rn:msg:SHOW_MORE_COMMUNITY_DISCUSSIONS_LBL#</a>
        </div>
    </div>
*/ ?>
</div>
