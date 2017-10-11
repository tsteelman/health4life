<?php
$this->Html->addCrumb(Configure::read('App.DASHBOARD_LABEL'), '/');
$this->Html->addCrumb('My Health', '/profile/myhealth');
$this->Html->addCrumb(h('My Health Record'));
?>
<div class="container">
    <div class="health_record">
        <div class="row edit">
    <div class="col-lg-3">
        
    <ul class="edit_profile_options">
        <li><h4><a href="#" class="selected">Personal History</a></h4></li>
        <li><h4><a href="#">Medical Conditions</a></h4></li>
        <li><h4><a href="#">Allergies</a></h4></li>
        <li><h4><a href="#">Immunizations</a></h4></li>           
    </ul>
            
    </div>
    <div class="col-lg-9">


        <div class="page-header">           
            <h2>
                <span>Personal information</span>&nbsp;
            </h2>         
        </div>
        <form>
            <div class="form-group">
                <div class="col-lg-5">
                    <label>Name<span class="red_star_span"> *</span></label>
                    <input type="text" class="form-control">
                </div>
                <div class="col-lg-5">
                    <label>Sex<span class="red_star_span"> *</span></label>
                    <select name="data[User][gender]" class="form-control" id="UserGender">
                        <option value="M" selected="selected">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
            </div>
             <div class="form-group">              
                <div class="col-lg-3">
                    <label>Age<span class="red_star_span"> *</span></label>
                    <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                   <div class="col-lg-3">
                    <label>Height<span class="red_star_span"> *</span></label>
                    <input type="text" class="form-control">
                </div>
                   <div class="col-lg-3">
                    <label>Weight<span class="red_star_span"> *</span></label>
                    <input type="text" class="form-control">
                </div>
            </div>
             <div class="form-group">
                  <div class="col-lg-5">
                    <label>Auto Populate Conditions</label>
                    <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="col-lg-5">
                    <label>Age of Diagnosis</label>
                    <input type="text" class="form-control">
                </div>               
            </div>
            <div class="form-group">
                 <div class="col-lg-5">
                    <label>Occupation</label>
                     <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                <div class="col-lg-5">
                    <label>Marital Status</label>
                     <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </div>
             <div class="form-group">                 
                <div class="col-lg-5">
                    <label>Race</label>
                     <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
            </div>
              <div class="form-group">              
                <div class="col-lg-3">
                    <label>Zip code</label>
                    <select name="" class="form-control" id="">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3" >3</option>
                        <option value="4">4</option>
                    </select>
                </div>
                   <div class="col-lg-3">
                    <label>City</label>
                    <input type="text" class="form-control">
                </div>
                   <div class="col-lg-3">
                    <label>State</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-next">Next  <img src="/theme/App/img/nxt_arow.png" alt="Next"></button>
            </div>
        </form>
        <div class="medical_conditions">
           <div class="page-header">           
            <h2>
                <span>Do you have, or have you had, any of the following</span>&nbsp;
            </h2>         
        </div> 
            <form>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                AIDS/HIV Positive,
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Epilepsy or Seizures
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Low Blood Pressure
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Alzheimer's Disease
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Excessive Bleeding
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Lung Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Anaphylaxis
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                               Excessive Thirst
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Mitral Valve Prolapse
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Anemia
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Fainting Spells/Dizziness
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Pain in Jaw Joints
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Angina
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Frequent Cough
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Parathyroid
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                               Arthritis/Gout
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Frequent Diarrhea
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Psychiatric Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Artificial Heart Valve
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Frequent Headaches
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Psychiatric Care
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Artificial Joint
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Genital Herpes
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Radiation Treatments
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Asthma
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Glaucoma
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Recent Weight Loss
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Blood Disease
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hay Fever
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Renal Dialysis
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Blood Transfusion
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Heart Attack
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Rheumatic Fever
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Breathing Problem
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Heart Murmur
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Rheumatism
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Bruise Easily
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Heart Pacemaker
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Scarlet Fever
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Cancer
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Heart Trouble/Disease
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Shingles
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Chemotherapy
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hemophilia
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Sickle Cell Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Chest Pains
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hepatitis A
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Sinus Trouble
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Cold Sores/Fever 
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hepatitis B or C
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Spina Bifida
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Blisters
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Herpes
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Stomach/Intestinal Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Congential Heart Disorder
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                High Blood Pressure
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Stroke
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Convulsions
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hives or Rash
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Swelling of Limbs
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Cortisone Medicine
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Hypoglycemia
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Thyroid Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Diabetes
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Irregular Heartbeat
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Tonsillitis
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Drug Addiction
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Kidney Problems
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Ulcers
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Easily Winded
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Leukemia,
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Venereal Disease
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Emphysema
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                         <div class="checkbox">
                            <label>
                                <input type="checkbox" value="">
                                Liver Disease
                            </label>
                        </div>
                    </div>                    
                </div>
                <div class="form-group">
                <button type="button" class="btn btn-next">Next  <img src="/theme/App/img/nxt_arow.png" alt="Next"></button>
            </div>
            </form>
        </div>
      </div>
        </div>      
            