<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span class="mws-i-24 i-sign-post">Form with Wizard Navigation</span>
	</div>
	<div class="mws-panel-body">
		<div class="mws-wizard clearfix">
			<ul>
				<li>
					<a class="mws-ic-16 ic-accept" href="#">Past Steps</a>
				</li>
				<li class="current">
					<a href="#" class="mws-ic-16 ic-delivery">Current Step</a>
				</li>
				<li>
					<a class="mws-ic-16 ic-user" href="#">Next Steps</a>
				</li>
				<li>
					<a class="mws-ic-16 ic-direction" href="#">Next Steps</a>
				</li>
			</ul>
		</div>
		<form class="mws-form" action="form_layouts.html">
			<div class="mws-form-inline">
				<div class="mws-form-row">
					<label>Title</label>
					<div class="mws-form-item large">
						<input type="text" class="mws-textinput" />
					</div>
				</div>
				<div class="mws-form-row">
					<label>Article</label>
					<div class="mws-form-item large">
						<textarea rows="100%" cols="100%"></textarea>
					</div>
				</div>
				<div class="mws-form-row">
					<label>Category</label>
					<div class="mws-form-item large">
						<select>
							<option>Option 1</option>
							<option>Option 3</option>
							<option>Option 4</option>
							<option>Option 5</option>
						</select>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<input type="submit" value="Prev" class="mws-button gray left" />
				<input type="submit" value="Next" class="mws-button green" />
			</div>
		</form>
	</div>
</div>