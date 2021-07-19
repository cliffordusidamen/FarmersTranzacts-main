<template>
	<div class="px-4 px-sm-0">
		<div class="d-flex justify-content-between">
			<div>
				<div class="h5">
					<slot name="title"></slot>
					<div class="d-block small mt-1 text-muted">
						<slot name="description"></slot>
					</div>
				</div>
			</div>

			<div>
				<slot name="aside"></slot>
			</div>
		</div>
	</div>
</template>
