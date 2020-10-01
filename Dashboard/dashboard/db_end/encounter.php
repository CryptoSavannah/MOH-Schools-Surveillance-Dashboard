<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/detail_page.php';
    include_once dirname(__FILE__) . '/' . 'components/page/nested_form_page.php';


    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class encounterPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->dataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`encounter`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, true);
            $field = new StringField('patient_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $field = new DateTimeField('time_recorded');
            $field->SetIsNotNull(true);
            $this->dataset->AddField($field, false);
            $this->dataset->AddLookupField('patient_id', 'patient', new StringField('patient_id'), new StringField('patient_id', 'patient_id_patient_id', 'patient_id_patient_id_patient'), 'patient_id_patient_id_patient');
            $this->dataset->AddLookupField('facility_id', 'facility', new StringField('facility_id'), new StringField('facility_name', 'facility_id_facility_name', 'facility_id_facility_name_facility'), 'facility_id_facility_name_facility');
            $this->dataset->AddLookupField('disease_id', 'disease', new StringField('disease_id'), new StringField('disease_name', 'disease_id_disease_name', 'disease_id_disease_name_disease'), 'disease_id_disease_name_disease');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id', 'id', 'Id'),
                new FilterColumn($this->dataset, 'patient_id', 'patient_id_patient_id', 'Patient Id'),
                new FilterColumn($this->dataset, 'facility_id', 'facility_id_facility_name', 'Facility Id'),
                new FilterColumn($this->dataset, 'disease_id', 'disease_id_disease_name', 'Disease Id'),
                new FilterColumn($this->dataset, 'time_recorded', 'time_recorded', 'Time Recorded')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id'])
                ->addColumn($columns['patient_id'])
                ->addColumn($columns['facility_id'])
                ->addColumn($columns['disease_id'])
                ->addColumn($columns['time_recorded']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('patient_id')
                ->setOptionsFor('facility_id')
                ->setOptionsFor('disease_id')
                ->setOptionsFor('time_recorded');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_edit');
            
            $filterBuilder->addColumn(
                $columns['id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('patient_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_patient_id_patient_id_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('patient_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_patient_id_patient_id_search');
            
            $filterBuilder->addColumn(
                $columns['patient_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('facility_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_facility_id_facility_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('facility_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_facility_id_facility_name_search');
            
            $filterBuilder->addColumn(
                $columns['facility_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new AutocompleteComboBox('disease_id_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_disease_id_disease_name_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('disease_id', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_disease_id_disease_name_search');
            
            $filterBuilder->addColumn(
                $columns['disease_id'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('time_recorded_edit', false, 'Y-m-d H:i:s');
            
            $filterBuilder->addColumn(
                $columns['time_recorded'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for patient_id field
            //
            $column = new TextViewColumn('patient_id', 'patient_id_patient_id', 'Patient Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for facility_name field
            //
            $column = new TextViewColumn('facility_id', 'facility_id_facility_name', 'Facility Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for disease_name field
            //
            $column = new TextViewColumn('disease_id', 'disease_id_disease_name', 'Disease Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for time_recorded field
            //
            $column = new DateTimeViewColumn('time_recorded', 'time_recorded', 'Time Recorded', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for patient_id field
            //
            $column = new TextViewColumn('patient_id', 'patient_id_patient_id', 'Patient Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for facility_name field
            //
            $column = new TextViewColumn('facility_id', 'facility_id_facility_name', 'Facility Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for disease_name field
            //
            $column = new TextViewColumn('disease_id', 'disease_id_disease_name', 'Disease Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for time_recorded field
            //
            $column = new DateTimeViewColumn('time_recorded', 'time_recorded', 'Time Recorded', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for patient_id field
            //
            $editor = new AutocompleteComboBox('patient_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patient`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('patient_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sex');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('patient_id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Patient Id', 'patient_id', 'patient_id_patient_id', 'edit_patient_id_patient_id_search', $editor, $this->dataset, $lookupDataset, 'patient_id', 'patient_id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for facility_id field
            //
            $editor = new AutocompleteComboBox('facility_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`facility`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('facility_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('location');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('in_patient');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('facility_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Facility Id', 'facility_id', 'facility_id_facility_name', 'edit_facility_id_facility_name_search', $editor, $this->dataset, $lookupDataset, 'facility_id', 'facility_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for disease_id field
            //
            $editor = new AutocompleteComboBox('disease_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`disease`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('disease_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('disease_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Disease Id', 'disease_id', 'disease_id_disease_name', 'edit_disease_id_disease_name_search', $editor, $this->dataset, $lookupDataset, 'disease_id', 'disease_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for time_recorded field
            //
            $editor = new DateTimeEdit('time_recorded_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Time Recorded', 'time_recorded', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for patient_id field
            //
            $editor = new AutocompleteComboBox('patient_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patient`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('patient_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sex');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('patient_id', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Patient Id', 'patient_id', 'patient_id_patient_id', 'insert_patient_id_patient_id_search', $editor, $this->dataset, $lookupDataset, 'patient_id', 'patient_id', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for facility_id field
            //
            $editor = new AutocompleteComboBox('facility_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`facility`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('facility_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('location');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('in_patient');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('facility_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Facility Id', 'facility_id', 'facility_id_facility_name', 'insert_facility_id_facility_name_search', $editor, $this->dataset, $lookupDataset, 'facility_id', 'facility_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for disease_id field
            //
            $editor = new AutocompleteComboBox('disease_id_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`disease`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('disease_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('disease_name', GetOrderTypeAsSQL(otAscending));
            $editColumn = new DynamicLookupEditColumn('Disease Id', 'disease_id', 'disease_id_disease_name', 'insert_disease_id_disease_name_search', $editor, $this->dataset, $lookupDataset, 'disease_id', 'disease_name', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for time_recorded field
            //
            $editor = new DateTimeEdit('time_recorded_edit', false, 'Y-m-d H:i:s');
            $editColumn = new CustomEditColumn('Time Recorded', 'time_recorded', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $this->RenderText($editColumn->GetCaption())));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for patient_id field
            //
            $column = new TextViewColumn('patient_id', 'patient_id_patient_id', 'Patient Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for facility_name field
            //
            $column = new TextViewColumn('facility_id', 'facility_id_facility_name', 'Facility Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for disease_name field
            //
            $column = new TextViewColumn('disease_id', 'disease_id_disease_name', 'Disease Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for time_recorded field
            //
            $column = new DateTimeViewColumn('time_recorded', 'time_recorded', 'Time Recorded', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id field
            //
            $column = new NumberViewColumn('id', 'id', 'Id', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for patient_id field
            //
            $column = new TextViewColumn('patient_id', 'patient_id_patient_id', 'Patient Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for facility_name field
            //
            $column = new TextViewColumn('facility_id', 'facility_id_facility_name', 'Facility Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for disease_name field
            //
            $column = new TextViewColumn('disease_id', 'disease_id_disease_name', 'Disease Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for time_recorded field
            //
            $column = new DateTimeViewColumn('time_recorded', 'time_recorded', 'Time Recorded', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for patient_id field
            //
            $column = new TextViewColumn('patient_id', 'patient_id_patient_id', 'Patient Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for facility_name field
            //
            $column = new TextViewColumn('facility_id', 'facility_id_facility_name', 'Facility Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for disease_name field
            //
            $column = new TextViewColumn('disease_id', 'disease_id_disease_name', 'Disease Id', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for time_recorded field
            //
            $column = new DateTimeViewColumn('time_recorded', 'time_recorded', 'Time Recorded', $this->dataset);
            $column->SetDateTimeFormat('Y-m-d H:i:s');
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setExportListAvailable(array('excel','word','xml','csv','pdf'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('excel','word','xml','csv','pdf'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patient`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('patient_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sex');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('patient_id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_patient_id_patient_id_search', 'patient_id', 'patient_id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`facility`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('facility_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('location');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('in_patient');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('facility_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_facility_id_facility_name_search', 'facility_id', 'facility_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`disease`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('disease_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('disease_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_disease_id_disease_name_search', 'disease_id', 'disease_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patient`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('patient_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sex');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('patient_id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_patient_id_patient_id_search', 'patient_id', 'patient_id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`facility`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('facility_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('location');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('in_patient');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('facility_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_facility_id_facility_name_search', 'facility_id', 'facility_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`disease`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('disease_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('disease_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_disease_id_disease_name_search', 'disease_id', 'disease_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`patient`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('patient_id');
            $lookupDataset->AddField($field, false);
            $field = new StringField('sex');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('patient_id', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_patient_id_patient_id_search', 'patient_id', 'patient_id', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`facility`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('facility_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('facility_name');
            $lookupDataset->AddField($field, false);
            $field = new StringField('location');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new IntegerField('in_patient');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('facility_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_facility_id_facility_name_search', 'facility_id', 'facility_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);$lookupDataset = new TableDataset(
                MySqlIConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`disease`');
            $field = new IntegerField('id', null, null, true);
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, true);
            $field = new StringField('disease_id');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $field = new StringField('disease_name');
            $field->SetIsNotNull(true);
            $lookupDataset->AddField($field, false);
            $lookupDataset->setOrderByField('disease_name', GetOrderTypeAsSQL(otAscending));
            $lookupDataset->AddCustomCondition(EnvVariablesUtils::EvaluateVariableTemplate($this->GetColumnVariableContainer(), ''));
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_disease_id_disease_name_search', 'disease_id', 'disease_name', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doGetCustomUploadFileName($fieldName, $rowData, &$result, &$handled, $originalFileName, $originalFileExtension, $fileSize)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doGetCustomPagePermissions(Page $page, PermissionSet &$permissions, &$handled)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
    }



    try
    {
        $Page = new encounterPage("encounter", "encounter.php", GetCurrentUserPermissionSetForDataSource("encounter"), 'UTF-8');
        $Page->SetTitle('Encounter');
        $Page->SetMenuLabel('Encounter');
        $Page->SetHeader(GetPagesHeader());
        $Page->SetFooter(GetPagesFooter());
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("encounter"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
