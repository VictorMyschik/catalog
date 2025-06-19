<table class="table table-hover table-compact">
    <tbody>
    @foreach($value as $group)
        <tr>
            <th colspan="2">{{ $group['group_name'] }}</th>
        </tr>
        @foreach($group['options'] as $attribute)
            <tr>
                <td>{{ $attribute['name'] }}</td>
                <td>
                    {!! $attribute['value'] !!}
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>