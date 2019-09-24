<div class="click-closed"></div>
<div class="box-collapse">
  <div class="title-box-d">
    <h3 class="title-d">Search Property</h3>
  </div>
  <span class="close-box-collapse right-boxed ion-ios-close"></span>
  <div class="box-collapse-wrap form">
      <form class="form-a">
        <div class="row">
          <div class="col-md-12 mb-2">
            <div class="form-group">
              <label for="Type">Keyword</label>
              <p class="help_small_user"><small>*Add user login to search user</small></p>
              <p class="help_small_user"><small>*To search users by tags just add tags with # (Example: #Matcha#UNIT#2019)</small></p>
              <input type="text" id="search_input" class="form-control" placeholder="Search" autocomplete="off">
            </div>
          </div>
          <div class="col-md-12 mb-2">
            <div class="row">
              <label class="name_SF">Search by same tags:</label>
              <input id="search_by_same_tags" type="checkbox" name="toppings" value=false><label for="search_by_same_tags" class="search_by_same_tags_label" data="0"><div class="search_sort_checker"></div></label>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <div class="form-group">
              <label for="country_search">Country</label>
              <select class="form-control" id="country_search">
                <option>Select Country</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-2">
            <div class="form-group">
              <label for="city">City</label>
              <select class="form-control" id="city_search">
                <option>All City</option>
              </select>
            </div>
          </div>

          <div class="col-md-12 mb-2">
            <div class="form-group">
              <label for="garages">Filter by</label>
                <div class="row">
                    <label class="name_SF">Age:</label>
                    <div class="col-md-12">
                        <div class="multi-range multi-range-double search_filter_range_age w-60 lef-98" data-lbound='10' data-ubound='60'>
                        <div class="multi_range_1"></div>
                        <input class="slider" type="range" min="10" step="1" max="60" value="60" oninput='this.parentNode.dataset.ubound=this.value;'>
                        <input class="slider" type="range" min="10" step="1" max="60" value="10" oninput='this.parentNode.dataset.lbound=this.value;'>
                        <div class="multi_range_2"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                   <label class="name_SF">Location Distance:</label>
                    <div class="col-md-12">
                        <div class='multi-range multi-range-one-km less_100 search_distance_inp w-60 search_distance lef-98' data-lbound='0'>
                        <input class="slider" type="range" min="0" step="1" max="100" value="0" oninput='setDistance(this);'>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="name_SF">Rating:</label>
                    <div class="col-md-12">
                       <div class='multi-range multi-range-one search_filter_rating w-60 lef-98' data-lbound='0'>
                        <input class="slider" type="range" min="0" step="1" max="100" value="0" oninput='this.parentNode.dataset.lbound=this.value;'>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: flex;flex-direction: column;">
                    <label class="name_SF">Interests:</label>
                    <div class="col-md-12">
                     <p class="help_small_user"><small>*Add interests with tag #</small></p>
                    <p class="help_small_user"><small>*To write new tag just press # or press add</small></p>
                    <div class="interests_inp_cont">
                            <input class="form-control f_inp_interests_foll" id="search_interestsHelpFilter" autocomplete="off" placeholder="Add your interests with tag #" oninput="search_tagHelperFilter(this.value)" value="#">
                            <p class="btn search_btn_inter_filter btn_input_interests_f">Add</p>
                    </div>
                    <div class="row pr-1 helperRel">
                        <div class="search_helperProfIntFilter helperAbs" style="display: none;"></div>
                    </div>
                    <p class="help_small_user"><small>To delete tag just click to tag</small></p>
                    <div class="col-md-12 form-group search_interest_cont_filter interests_cont_filter_foll"></div>
                    </div>
                </div>
            </div>
          </div>

          <div class="col-md-12 mb-2">
            <div class="form-group">
              <label for="bedrooms">Sort by</label>
                <div class="row">
                    <label class="name_SF">Age:</label>
                    <input id="age__ham" class="search_sort_age search_imp_check" type="checkbox" name="toppings" value=false><label for="age__ham" class="search_lab_check" data="0"><div class="search_sort_checker"></div></label>
                </div>
                <div class="row">
                    <label class="name_SF">Distance:</label>
                    <input id="location_ham" class="search_sort_location search_imp_check" type="checkbox" name="toppings" value=false><label for="location_ham" class="search_lab_check" data="0"><div class="search_sort_checker"></div></label>
                </div>
                <div class="row">
                    <label class="name_SF">Rating:</label>
                       <input id="rating_ham" class="search_sort_rating search_imp_check" type="checkbox" name="toppings" value=false><label for="rating_ham" class="search_lab_check" data="0"><div class="search_sort_checker"></div></label>
                </div>
                <div class="row">
                    <label class="name_SF">Same tags:</label>
                       <input id="same_tags_ham" class="search_sort_same_tags search_imp_check" type="checkbox" name="toppings" value=false><label for="same_tags_ham" class="search_lab_check" data="0"><div class="search_sort_checker"></div></label>
                </div>
                <div class="row" style="display: flex;flex-direction: column;">
                    <label class="name_SF">Interests:</label>
                    <div class="col-md-12">
                    <p class="help_small_user"><small>*Add interests with tag #</small></p>
                    <p class="help_small_user"><small>*To write new tag just press # or press add</small></p>
                    <div class="interests_inp_cont">
                            <input class="form-control f_inp_interests_foll" id="search_interestsHelpSort" autocomplete="off" placeholder="Add your interests with tag #" oninput="search_TagHelperSort(this.value)" value="#">
                            <p class="btn btn_inter search_btn_inter_sort btn_input_interests_f">Add</p>
                    </div>
                    <div class="row pr-1 helperRel">
                        <div class="search_helperProfIntSort helperAbs" style="display: none;"></div>
                    </div>
                    <p class="help_small_user"><small>To delete tag just click to tag</small></p>
                    <div class="col-md-12 form-group interests_cont_filter_foll search_interest_cont_sort"></div>
                    </div>
                </div>
                <div class="row">
                    <label class="name_SF">Sorted by:</label>
                    <div class="col-md-12 search_by_cont">
                        <input id="search_toggle-on" class="toggle-left search_toggle" name="search_toggle" value="ASC" type="radio" checked>
                        <label for="search_toggle-on" class="btn_lab btn">Ascending</label>
                        <input id="search_toggle-off" class=" toggle-right search_toggle" name="search_toggle" value="DESC" type="radio">
                        <label for="search_toggle-off" class="btn_lab btn">Descending</label>
                        <input type="hidden" name="search_sorted_by">
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-12">
            <p class="btn btn-b search_f_btn">Search</p>
          </div>
        </div>
      </form>
    </div>
  </div>