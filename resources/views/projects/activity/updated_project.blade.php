@if (count($activity->changes['after'])==1)

    You updated the project {{ key($activity->changes['after']) }} of the project

@else
    
    You updated the project

@endif
