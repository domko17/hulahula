<table class="table table-striped table-condensed" id="table_unpaid_mobile" style="display: none; width: 100%">
    <tbody>
    @foreach($orders_unpaid as $i)
        <tr>
            <td><b>{{ $i->variable_symbol }}</b></td>
            <td>
                <a href="{{ route('user.profile', $i->user->id) }}"
                   class="text-primary">{{ $i->user->name }}</a>
                <br>
                <i class="fa fa-star"></i> {{ $i->getName() }} <br>
                {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}
            </td>
            <td><b>{{ $i->price }} €</b></td>
            <td>
                <button type="button" data-item-id="{{$i->id}}"
                        class="btn btn-inverse-success btn-sm confirm-alert"><i
                        class="fa fa-fw fa-money"></i></button>
                {{ Form::open(['method' => 'POST',
                'route' => ['admin.package-orders.sign_as_paid', $i->id],
                'id' => 'item-conf-'. $i->id  ]) }}
                {{ Form::hidden('order_id', $i->id) }}
                {{ Form::close() }}

                <button type="button" data-item-id="{{ $i->id }}"
                        class="btn btn-inverse-danger btn-sm delete-alert"><i
                        class="fa fa-fw fa-times"></i></button>
                {{ Form::open(['method' => 'DELETE',
                'route' => ['admin.package-orders.destroy', $i->id],
                'id' => 'item-del-'. $i->id  ]) }}
                {{ Form::hidden('order_id', $i->id) }}
                {{ Form::close() }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-striped" id="table_unpaid_pc" style="display: none">
    <tbody>
    @foreach($orders_unpaid as $i)
        <tr>
            <td>#{{ $i->id }}</td>
            <td><b>{{ $i->variable_symbol }}</b></td>
            <td><a href="{{ route('user.profile', $i->user->id) }}"
                   class="text-primary">{{ $i->user->name }}</a></td>
            {{--<td>{{ $i->getName() }}</td>
            <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}</td>--}}
            <td><b>{{ $i->price }} €</b></td>
            <td>
                <button type="button" data-item-id="{{ $i->id }}"
                        class="btn btn-inverse-danger btn-sm pull-right delete-alert"><i
                        class="fa fa-times"></i></button>
                {{ Form::open(['method' => 'DELETE',
                'route' => ['admin.package-orders.destroy', $i->id],
                'id' => 'item-del-'. $i->id  ]) }}
                {{ Form::hidden('order_id', $i->id) }}
                {{ Form::close() }}

                <button type="button" data-item-id="{{$i->id}}"
                        class="btn btn-inverse-success btn-sm pull-right confirm-alert"><i
                        class="fa fa-money "></i></button>
                {{ Form::open(['method' => 'POST',
                'route' => ['admin.package-orders.sign_as_paid', $i->id],
                'id' => 'item-conf-'. $i->id  ]) }}
                {{ Form::hidden('order_id', $i->id) }}
                {{ Form::close() }}

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
