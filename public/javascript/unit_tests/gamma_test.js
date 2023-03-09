test( "gamma trim", function() {
  equal( dmsInput.trim(" Passed! "), "Passed!" );
});

test("gamma remove", function() {
	expect(1);
	var target = ['one', 'two', 'three', 'four', 'five'];
	var remove = ['two', 'four'];
	var expected = ['one', 'three', 'five'];
	var result = gamma.removeItems(target, remove);
	deepEqual( result, expected, "Array items successfully removed");
});
