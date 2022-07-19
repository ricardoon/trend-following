<script src="{{ mix('js/app.js') }}" defer></script>
<script type="text/javascript">
    let dollarUS = Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    });
</script>
@stack('head.scripts')
