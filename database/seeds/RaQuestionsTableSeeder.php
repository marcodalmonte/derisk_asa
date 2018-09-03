<?php

use Illuminate\Database\Seeder;

class RaQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $creation_date = date('Y-m-d H:i:s',time());
        $modify_date = date('Y-m-d H:i:s',time());
        
        $raquestions = array(
            array(
                'rasection_id'  =>  '1',
                'question'      =>  'Do the fixed installations appear to be in good condition?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '1',
                'question'      =>  'Fixed installation periodically inspected and tested by a competent person?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '1',
                'question'      =>  'Are portable electrical appliances subject to PAT testing?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '1',
                'question'      =>  'Is there a policy in place regarding the use of personal appliances belonging to members of staff or contractors?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '1',
                'question'      =>  'Where present, is the use of extension cables acceptable?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '2',
                'question'      =>  'Smoking prohibited in the building?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '2',
                'question'      =>  'Does this policy appear to be observed at all times?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '3',
                'question'      =>  'Does basic security against arson by outsiders appear reasonable?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '3',
                'question'      =>  'Where combustible materials (refuse etc.) are stored outside of the premises, are they stored in secure bins away from the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '3',
                'question'      =>  'Are adequate arrangements in place to ensure that refuse is removed from the premises to prevent an unnecessary build-up of the fire loading?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '4',
                'question'      =>  'Is the use of portable heaters avoided as far as practicable?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '4',
                'question'      =>  'Are fixed heating installations subject to regular maintenance?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '4',
                'question'      =>  'Where portable heaters are provided, are they positioned away from combustible materials in a safe position?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '5',
                'question'      =>  'Does the building have a lightning protection system?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '5',
                'question'      =>  'Is the earth integrity of any fixed fire installations and building lightning conductor tested to the requirements of BS6651?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '6',
                'question'      =>  'Are cooking appliances subject to adequate periodic cleaning?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '6',
                'question'      =>  'Are the extract ductwork, canopy and filters subject to adequate periodic cleaning?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '6',
                'question'      =>  'Are gas shut off buttons provided in suitable locations in the kitchen?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '6',
                'question'      =>  'Is a fire blanket provided in a suitable location in the kitchen?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '6',
                'question'      =>  'Are suitable fire extinguishers provided in the kitchen?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '7',
                'question'      =>  'Where outside contractors are employed to carry out building and maintenance works, are they always "company approved" contractors?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '7',
                'question'      =>  'Where hot works are carried out, is there a "hot works permit" procedure in place?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '7',
                'question'      =>  'When contractors from other employers come to carry out building or maintenance works at the premises, are the contractors and their employees provided with basic fire safety information about the premises (Fire risks present at the premises, emergency escape routes and exits, actions to be taken in the case of a fire etc.)?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '8',
                'question'      =>  'Are combustible materials kept separate from ignition sources?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '8',
                'question'      =>  'Is combustible waste stored appropriately and kept to a reasonable level?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '8',
                'question'      =>  'Are there are unnecessary combustibles stored on the premises?',
                'goal'          =>  'No',
            ),
            
            array(
                'rasection_id'  =>  '8',
                'question'      =>  'Are storage arrangements in place to ensure that the escape routes are maintained free from obstruction at all times?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '8',
                'question'      =>  'Are "protected escape routes" kept clear of combustible materials at all times?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '9',
                'question'      =>  'Are there any dangerous/hazardous substances stored on the premises?',
                'goal'          =>  'No',
            ),
            
            array(
                'rasection_id'  =>  '9',
                'question'      =>  'Are the levels of dangerous/hazardous materials kept to a minimum?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '9',
                'question'      =>  'Are any dangerous/hazardous materials stored appropriately?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '9',
                'question'      =>  'If any compressed gases are kept on the premises, are they stored in secure, ventilated cages?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '9',
                'question'      =>  'Where dangerous/hazardous materials are stored, is appropriate warning signage provided?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '10',
                'question'      =>  'Are any other fire hazards, which are not mentioned in previous sections being managed appropriately?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Taking into account the size, layout and occupancy of the premises are there adequate number of exits of adequate size?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are there adequate means of escape from all parts of the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are travel distances from all parts of the premises to the final exit acceptable?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are all final exit doors and all doors leading to those exits easily opened, with quick release mechanisms (and without the need of a key)?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Is adequate signage provided, giving instruction on how to operate those release mechanisms?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Do all final exit doors, and doors leading to those exits open in the direction of travel?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Is adequate and appropriate signage provided to indicate the final exits and routes to those exits?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are escape routes kept clear from any obstructions?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are escape routes free from any trip or slip hazard?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where required to be "protected" escape routes, are those routes provided with adequate protection from fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are those "protected" escape routes kept clear of combustible materials?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Do all doors leading from rooms/storage areas into protected escape routes provide adequate protection from fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are all doors leading from rooms/storage areas into protected escape routes provided with self- closing devices complying with current standards, (or in the case of store rooms and service riser cupboards kept locked at all times?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where necessary, are doors leading from rooms/storage areas/riser cupboards provided with intumescent strips and cold smoke seals?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are all doors, (where necessary), provided with appropriate signage?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are all fire doors in good condition?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'When released, do all fire doors close fully into their frames?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'When fire doors are closed into their frames, are the gaps between the doors and the frames acceptable? (The gap should be no more than 4-5mm).',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are any fire doors being wedged open?',
                'goal'          =>  'No',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are escape routes provided with adequate artificial lighting?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are escape routes provided with adequate emergency lighting?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where revolving doors are present, are emergency escape doors provided adjacent or close to the revolving doors?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where electronic sliding doors are present, do they "fail safe" to the open position on actuation of the fire alarm, or in the event of mains power failure?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where inner rooms exist, are adequate provisions in place to ensure the safety of persons in those rooms?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where necessary, are AOVs provided in escape corridors and staircases?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where external emergency escape stairs and platforms are provided, are they in good condition and adequately protected from fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Are external emergency stairs/routes provided with directional signage, standard lighting and emergency lighting?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '11',
                'question'      =>  'Where any emergency exit routes to a place of final safety are shared with other premises, are these routes adequate, and suitably managed?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '12',
                'question'      =>  'Is adequate fire separation provided between the premises and any adjacent premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '12',
                'question'      =>  'Is adequate fire separation provided within the premise?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '12',
                'question'      =>  'Where fire resisting walls have been breached by services passing through them, have any holes been made good with fire resisting materials?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '12',
                'question'      =>  'Where ventilation ducting passes between separate fire compartments are dampers provided?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '12',
                'question'      =>  'Are wall linings reasonably fire resisting so as to limit the spread of fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Does the premises require an electronic fire alarm?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Where an electronic fire alarm system is necessary, is one provided?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Where an electronic fire alarm system is provided, is that system of the appropriate standard and category to ensure the safety of all persons on the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Does the alarm appear to be in good working order?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Should a fault occur in the fire alarm system, are arrangements in place for the fault to be corrected within a reasonable timescale, and for any necessary interim measures to be implemented?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Is the fire alarm remotely monitored?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Have any false alarms occurred which have resulted in the fire brigade being called unnecessarily? (Unwanted fire signals)',
                'goal'          =>  'No',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Are false alarms recorded and investigated, and actions taken to prevent similar events from reoccurring?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '13',
                'question'      =>  'Does standard and category of the fire alarm system support the evacuation strategy for the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '14',
                'question'      =>  'Has a reasonable provision of portable extinguishers been made, taking into account the various fire risks on the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '14',
                'question'      =>  'Are extinguishers readily available, but positioned so as not to impede any escape route?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Are the premises provided with a wet or dry rising main?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Where the premises are provided with a wet or dry rising main, are the inlet and outlet boxes secure to prevent vandalism, but provided with adequate signage, and accessible for firefighting personnel?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Are the premises provided with any form of suppression system?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Where any fixed systems are provided, is adequate signage provided to indicate the location of these facilities and associated controls?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Where high voltage luminous tube signs are present, have appropriately positioned fire fighter cut-off switches been provided?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '15',
                'question'      =>  'Are there any fire engineered solutions employed on the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '16',
                'question'      =>  'Is there suitable record of the fire safety arrangements?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '16',
                'question'      =>  'Have sufficient competent persons been appointed to assist the responsible person in undertaking the preventative and protective measures?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '16',
                'question'      =>  'Have all deficiencies identified in the previous FRA been addressed?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '16',
                'question'      =>  'Are routine in-house inspections of the fire safety provisions for the premises carried out?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Are the procedures to be followed in the case of a fire documented?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Are those procedures site specific?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include adequate arrangements for raising the alarm?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include adequate arrangements to evacuate the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include adequate arrangements to evacuate vulnerable persons from the premises? (Young, old, disabled).',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include adequate arrangements for calling the fire brigade?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include the location of the assembly point?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures specify the person in charge of the evacuation?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Do the procedures include arrangements to meet the fire brigade on their arrival, and to provide them with relevant information?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '17',
                'question'      =>  'Are fire action notices displayed adjacent to all manual call points?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Are all employees provided with fire safety training on induction and periodically thereafter?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the location of exits routes and final exits?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the location of the call points?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include how to operate the call points?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the location of the extinguishers?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the type of extinguisher to be used on any particular type of fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the operation of each type of extinguisher?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the science of fire?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the fire risks specific to the premises?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the need for good housekeeping?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Does the training include the need to protect fire escape routes and keep them clear?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Where necessary do some staff receive additional fire safety training, (such as fire warden training)?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Where necessary, do some staff receive additional training to assist in the evacuation of disabled persons?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Are controls in place to ensure that, where they are employed, night staff as well as day staff receive fire safety training?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Are records maintained of all fire safety training?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Are periodic fire drills carried out?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '18',
                'question'      =>  'Are fire drills recorded, and any deficiencies or problems addressed?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Is the fire alarm system tested weekly by the occupier and periodically tested and serviced by a competent engineer?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'When the fire alarm is tested, are automatic door holding devices also being checked to ensure that they release on actuation of the alarm?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Where Dorgard automatic door holders are fitted, are they all provided with floor plates?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'When the fire alarm is tested and serviced by a competent engineer, are AOVs also checked to ensure that they actuate correctly on actuation of the alarm?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are fire routes and exits, internal and external, checked periodically as necessary?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are fire doors checked periodically as necessary?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are any external emergency escape staircases and platforms checked periodically?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are emergency lights tested monthly by the occupier, and tested and serviced 12 monthly by a competent electrician?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are fire extinguishers being checked/maintained 12 monthly by a competent engineer?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are fire lifts being tested weekly by the occupier, and fully tested and serviced 6 monthly by a competent engineer?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are dry/wet risers being tested/serviced 6 monthly by a competent engineer?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are any locks, which are provided to allow access for fire service personnel checked periodically?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Is the sprinkler system being tested weekly by the occupier, and fully serviced by a competent engineer?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Is the Ansul system subject to periodic testing and maintenance?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Is the gas supply, and all associated equipment subject to annual testing and servicing?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are any facilities and items of equipment provided to assist in the evacuation of disabled persons periodically tested as recommended by the manufacturer’s instructions?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'Are records maintained of all testing and maintenance?',
                'goal'          =>  'Yes',
            ),
            
            array(
                'rasection_id'  =>  '19',
                'question'      =>  'When defects are identified in any of the fire safety systems, are there adequate procedures in place to ensure that these defects are rectifier within a reasonable timescale, and that any interim control measures are implemented?',
                'goal'          =>  'Yes',
            ),
        );
        
        foreach ($raquestions as $raquestion) {
            DB::table('raquestions')->insert([
                'rasection_id'  =>  $raquestion['rasection_id'],   
                'question'      =>  $raquestion['question'],
                'goal'          =>  $raquestion['goal'],
                'created_at'    =>  $creation_date,
                'updated_at'    =>  $modify_date,
            ]);
        }
    }
}
