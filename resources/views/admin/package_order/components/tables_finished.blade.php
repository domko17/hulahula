<table class="table table-striped table-condensed" id="table_paid_mobile" style="display: none; width: 100%">
    <thead>
    <tr>
        <th>ID</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders_finished as $i)
        <tr>
            <td>#{{ $i->id }}</td>
            <td><b>{{ $i->variable_symbol }}</b></td>
            <td>
                <a href="{{ route('user.profile', $i->user->id) }}"
                   class="text-primary">{{ $i->user->name }}</a><br>
                <i class="fa fa-star"></i> {{ $i->getName() }}<br>
                {{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}
            </td>
            <td><b>{{ $i->price }} €</b></td>
            <td>
                <span class="badge badge-gradient-{{ $i->paid ? "success" : "secondary" }}">
                                        {{ $i->paid ? __('order.paid') : __('order.canceled') }}</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-striped" id="table_paid_pc" style="display: none;">
    <thead>
    <tr>
        <th>ID</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders_finished as $i)
        <tr>
            <td>#{{ $i->id }}</td>
            <td><b>{{ $i->variable_symbol }}</b></td>
            <td><a href="{{ route('user.profile', $i->user->id) }}"
                   class="text-primary">{{ $i->user->name }}</a></td>
            <td>{{ $i->getName() }}</td>
            <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$i->created_at)->format("d.m.Y") }}</td>
            <td><b>{{ $i->price }} €</b></td>
            <td><span
                    class="badge badge-gradient-{{ $i->paid ? "success" : "secondary" }}">
                                        {{ $i->paid ? __('order.paid') : __('order.canceled') }}
                                    </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
