<select name="phone_type" class="form-control">
    <option value="feature phone" {{ $phone_type == 'feature phone' ? 'selected' : '' }}>Feature Phone</option>
    <option value="smartphone" {{ $phone_type == 'smartphone' ? 'selected' : '' }}>Smartphone</option>
</select>
