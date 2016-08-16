<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
	
<form  method='post' action='<?php echo $this->data['action']; ?>'>
                    <div class="rn_SearchInput">
                    	<span class='chunk' style='width:53% !important'>
                    	<label style='position:relative;bottom:6px'>Search</label>
                        <!--<rn:widget path="search/KeywordText" label_text="#rn:msg:FIND_THE_ANSWER_TO_YOUR_QUESTION_CMD#" initial_focus="true"/> -->
                        <input type='text' name='searchText' id='searchText' value='<?php echo $this->data['search_text']; ?>' placeholder="Search your text here !">
                        </span>
                        <span class='chunk'>
                        <label>Order By</label>
                        
                        <select name='incident_order_by'>
                        <option value="">--SELECT--</option> 	
                        <?php 
                        $headers=$this->data['headers'];
						foreach($headers as $key=>$value):
							$sel=($value==$this->data['order_by'])?"selected='selected'":"";
							echo "<option value='$value' $sel>$key</option>";
                        endforeach	
						?>
                        </select>
                        </span>
                        <span class='chunk'>
                        <label>Sort By</label>
                        <select name='incident_sort_by'>
                        	<option value='1' <?php echo ($this->data['sort_by']==1)?"selected='selected'":""; ?> >Ascending</option>
                        	<option value='2' <?php echo ($this->data['sort_by']==2)?"selected='selected'":""; ?> >Descending</option>
                        </select>
                        </span>
                        <span class='chunk'>
                        	<!--<rn:widget path="search/SearchButton" force_page_flip="true"/>-->
                        <button type='submit' name='submit_filter_search' value='search'>Search</button>
                        <button type="submit" name="reset_filter_search" value='reset'>Reset</button>	
                        </span>
                    </div>                    
                    
</form>
</div>
